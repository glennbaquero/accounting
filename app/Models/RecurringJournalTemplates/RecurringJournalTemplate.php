<?php

namespace App\Models\RecurringJournalTemplates;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use App\Models\RecurringJournalTemplateLines\RecurringJournalTemplateLine;
use App\Models\Journals\GeneralJournal;
use App\Models\JournalLines\GeneralJournalVoucher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecurringJournalTemplate extends Model
{
    const STATUS_ACTIVE = 'Active';
    const STATUS_PAUSED = 'Paused';
    const STATUS_COMPLETED = 'Completed';

    const FREQUENCY_DAILY = 'Daily';
    const FREQUENCY_WEEKLY = 'Weekly';
    const FREQUENCY_MONTHLY = 'Monthly';
    const FREQUENCY_QUARTERLY = 'Quarterly';
    const FREQUENCY_ANNUALLY = 'Annually';

    /**
     * @Relationship
     */

    public function template_lines() {
        return $this->hasMany(RecurringJournalTemplateLine::class, 'template_id', 'template_id')->withTrashed();
    }

    public function runs() {
        return $this->hasMany(RecurringJournalRun::class, 'template_id', 'template_id');
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'client' => $this->client->name ?? null,
            'template_id' => $this->template_id,
            'template_name' => $this->template_name,
            'description' => $this->description,
            'journal_name' => $this->journal_name,
            'frequency' => $this->frequency,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['template_id', 'template_name', 'description', 'client_id', 'journal_name', 'journal_type', 'account_type', 'cost_center', 'department', 'expense_purpose', 'frequency', 'start_date', 'end_date', 'occurrences_limit', 'created_by', 'updated_by'])
    {
        $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

        if (!$item) {
            $vars['status'] = static::STATUS_ACTIVE;
            $vars['next_run_date'] = $vars['start_date'] ?? now();
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    /**
     * Given a frequency, compute the next run date from a starting date.
     *
     * @param  \Carbon\Carbon|string  $from
     * @return \Carbon\Carbon
     */
    public function computeNextRunDate($from) {
        $date = Carbon::parse($from);

        switch ($this->frequency) {
            case static::FREQUENCY_DAILY:
                return $date->addDay();
            case static::FREQUENCY_WEEKLY:
                return $date->addWeek();
            case static::FREQUENCY_QUARTERLY:
                return $date->addMonthsNoOverflow(3);
            case static::FREQUENCY_ANNUALLY:
                return $date->addYear();
            case static::FREQUENCY_MONTHLY:
            default:
                return $date->addMonthNoOverflow();
        }
    }

    /**
     * Generate a real General Journal (header + vouchers) from this
     * template's lines, log the run, advance next_run_date, and complete
     * the template once its end date/occurrence limit is reached.
     *
     * Runs as the template's own creator (via Auth::loginUsingId) so it
     * behaves correctly whether triggered by a logged-in user (manual
     * "Run Now") or the unattended scheduler.
     *
     * @return \App\Models\Journals\GeneralJournal
     */
    public function generateJournalEntry() {
        Auth::loginUsingId($this->created_by);

        $count = GeneralJournal::withTrashed()->count() + 1;
        $number = str_pad($count, 4, '0', STR_PAD_LEFT);
        $runDate = now();

        $journalRequest = new Request();
        $journalRequest->merge([
            'general_journal_number' => $number,
            'invoice_journal_batch_number' => $this->template_id,
            'journal_name_number' => $number,
            'journal_name' => $this->journal_name ?: $this->template_name,
            'description' => ($this->description ?: $this->template_name) . ' (auto-generated from recurring template ' . $this->template_id . ')',
            'journal_status' => 'Open',
            'journal_type' => $this->journal_type ?: '---',
            'account_type' => $this->account_type ?: '---',
            'document' => '---',
            'bank_account' => '---',
            'used_by_user' => 'System',
            'protest_settlements' => '---',
            'protest_settled_process' => '---',
            'locked_by_system' => '---',
            'private_for_user_group' => '---',
            'financial_dimensions' => '---',
            'cost_center' => $this->cost_center ?: '---',
            'department' => $this->department ?: '---',
            'expense_purpose' => $this->expense_purpose ?: '---',
            'client_id' => $this->client_id,
            'created_by' => auth()->user()->fullname,
            'updated_at' => null,
        ]);

        $journal = GeneralJournal::store($journalRequest);

        $lines = $this->template_lines()->get();

        foreach ($lines as $index => $line) {
            $voucherNumber = $journal->general_journal_number . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            GeneralJournalVoucher::create([
                'general_journal_voucher_number' => $voucherNumber,
                'general_journal_number' => $journal->general_journal_number,
                'invoice_journal_batch_number' => $this->template_id,
                'journal_name' => $journal->journal_name,
                'voucher_line_number' => $index + 1,
                'voucher_date' => $runDate,
                'invoice_date' => $runDate,
                'due_date' => $runDate,
                'vendor_invoice_number' => '---',
                'payment_id' => '---',
                'method_of_payment' => '---',
                'terms_of_payment' => '---',
                'bank_transaction_type' => '---',
                'bank_account' => '---',
                'payment_specification' => '---',
                'payment_deposit_slip' => '---',
                'main_account' => $line->main_account,
                'account_type' => $this->account_type ?: '---',
                'offset_account_type' => '---',
                'offset_account' => '---',
                'description' => $line->description ?: $this->description,
                'debit_amount' => $line->debit_amount,
                'credit_amount' => $line->credit_amount,
                'cost_center' => $this->cost_center ?: '---',
                'department' => $this->department ?: '---',
                'expense_purpose' => $this->expense_purpose ?: '---',
                'created_by' => auth()->user()->fullname,
                'client_id' => $this->client_id,
                'company_id' => auth()->user()->company_id,
            ]);
        }

        RecurringJournalRun::create([
            'template_id' => $this->template_id,
            'general_journal_number' => $journal->general_journal_number,
            'run_date' => $runDate,
            'created_by' => $this->created_by,
        ]);

        $nextRunDate = $this->computeNextRunDate($this->next_run_date ?: $runDate);

        $vars = [
            'last_run_date' => $runDate,
            'next_run_date' => $nextRunDate,
            'occurrences_generated' => $this->occurrences_generated + 1,
        ];

        if (($this->end_date && $nextRunDate->gt(Carbon::parse($this->end_date)))
            || ($this->occurrences_limit && $vars['occurrences_generated'] >= $this->occurrences_limit)) {
            $vars['status'] = static::STATUS_COMPLETED;
        }

        $this->update($vars);

        return $journal;
    }

    /**
     * Renderers
     */

    public function renderShowUrl() {
        return route('recurring-journal-templates.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('recurring-journal-templates.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('recurring-journal-templates.restore', $this->id);
    }
}
