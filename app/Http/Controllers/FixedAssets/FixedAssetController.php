<?php

namespace App\Http\Controllers\FixedAssets;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\FixedAssets\FixedAssetStoreRequest;
use App\Models\FixedAssets\FixedAsset;
use App\Models\FixedAssetDepreciationLines\FixedAssetDepreciationLine;
use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\Ledgers\Ledger;
use App\Models\MainAccounts\MainAccount;

use DB;
use Carbon\Carbon;

class FixedAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fixed-assets.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $count = FixedAsset::withTrashed()->count() + 1;
        $asset_id = 'FA-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        return view('fixed-assets.create', [
            'asset_id' => $asset_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\FixedAssets\FixedAssetStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(FixedAssetStoreRequest $request)
    {
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = FixedAsset::store($request);

        $message = "You have successfully created {$item->asset_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = FixedAsset::withTrashed()->findOrFail($id);

        return view('fixed-assets.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FixedAssets\FixedAssetStoreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FixedAssetStoreRequest $request, $id)
    {
        $item = FixedAsset::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->asset_name}";
        $request['updated_by'] = auth()->user()->id;

        $item = FixedAsset::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = FixedAsset::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->asset_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = FixedAsset::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->asset_name}",
        ]);
    }

    /**
     * Generate (or extend) a straight-line monthly depreciation schedule for
     * this asset. Already-posted lines are left untouched; unposted lines
     * are rebuilt evenly across the remaining useful life so the schedule
     * always foots to (acquisition cost - salvage value).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateSchedule(Request $request, $id)
    {
        $asset = FixedAsset::withTrashed()->findOrFail($id);

        if ($asset->asset_status == FixedAsset::STATUS_DISPOSED) {
            throw ValidationException::withMessages([
                'message' => ['Cannot generate a schedule for a disposed asset.']
            ]);
        }

        /* Rebuild unposted lines only; posted history is never touched */
        FixedAssetDepreciationLine::where('asset_id', $asset->asset_id)->where('posted_checkbox', false)->get()->each(function ($line) {
            $line->forceDelete();
        });

        $months = max((int) $asset->useful_life_months, 1);
        $depreciable_base = (float) $asset->acquisition_cost - (float) $asset->salvage_value;

        $posted_lines = FixedAssetDepreciationLine::where('asset_id', $asset->asset_id)->where('posted_checkbox', true)->orderBy('period_number')->get();
        $already_posted = $posted_lines->count();
        $accumulated = (float) $posted_lines->sum('depreciation_amount');

        $remaining_months = $months - $already_posted;

        if ($remaining_months <= 0) {
            return response()->json([
                'message' => 'This asset is already fully depreciated; there are no remaining periods to schedule.'
            ]);
        }

        $remaining_base = $depreciable_base - $accumulated;
        $monthly_amount = round($remaining_base / $remaining_months, 2);
        $running_total = 0;

        $acquisition_date = Carbon::parse($asset->acquisition_date);

        for ($i = 1; $i <= $remaining_months; $i++) {
            $period_number = $already_posted + $i;
            $period_date = $acquisition_date->copy()->addMonthsNoOverflow($period_number)->endOfMonth();

            $amount = $i == $remaining_months ? round($remaining_base - $running_total, 2) : $monthly_amount;
            $running_total += $amount;
            $accumulated += $amount;

            $fiscal_period = FiscalPeriod::whereDate('fiscal_period_start_date', '<=', $period_date)->whereDate('fiscal_period_end_date', '>=', $period_date)->first();

            FixedAssetDepreciationLine::create([
                'asset_id' => $asset->asset_id,
                'period_number' => $period_number,
                'period_date' => $period_date,
                'fiscal_period_id' => $fiscal_period->fiscal_period_id ?? null,
                'fiscal_period_code' => $fiscal_period->fiscal_period_code ?? null,
                'depreciation_amount' => $amount,
                'accumulated_depreciation' => $accumulated,
                'book_value' => (float) $asset->acquisition_cost - $accumulated,
                'client_id' => $asset->client_id,
                'company_id' => $asset->company_id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }

        return response()->json([
            'message' => "Depreciation schedule generated for {$remaining_months} remaining period(s)."
        ]);
    }

    /**
     * Post a single depreciation line to the General Ledger:
     * Dr Depreciation Expense / Cr Accumulated Depreciation.
     *
     * @param  int  $id
     * @param  int  $lineId
     * @return \Illuminate\Http\Response
     */
    public function postDepreciationLine(Request $request, $id, $lineId)
    {
        $asset = FixedAsset::withTrashed()->findOrFail($id);
        $line = FixedAssetDepreciationLine::where('asset_id', $asset->asset_id)->where('id', $lineId)->firstOrFail();

        $this->postDepreciationLineInternal($asset, $line);

        return response()->json([
            'message' => "Successfully posted depreciation for period {$line->period_number}."
        ]);
    }

    /**
     * Post every unposted line whose period date has already passed.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postAllDue(Request $request, $id)
    {
        $asset = FixedAsset::withTrashed()->findOrFail($id);

        $lines = FixedAssetDepreciationLine::where('asset_id', $asset->asset_id)
            ->where('posted_checkbox', false)
            ->whereDate('period_date', '<=', now())
            ->orderBy('period_number')
            ->get();

        if (!$lines->count()) {
            return response()->json(['message' => 'No due, unposted depreciation lines found.']);
        }

        foreach ($lines as $line) {
            $this->postDepreciationLineInternal($asset, $line);
        }

        return response()->json([
            'message' => "Successfully posted {$lines->count()} depreciation period(s)."
        ]);
    }

    protected function postDepreciationLineInternal($asset, $line)
    {
        if ($line->posted_checkbox) {
            throw ValidationException::withMessages([
                'message' => ["Period {$line->period_number} has already been posted."]
            ]);
        }

        if (!$asset->depreciation_expense_account || !$asset->accumulated_depreciation_account) {
            throw ValidationException::withMessages([
                'message' => ['This asset is missing its depreciation expense or accumulated depreciation account.']
            ]);
        }

        $general_ledger = $this->resolveGeneralLedger($asset->client_id, $line->period_date);

        DB::beginTransaction();

        $gl_line = $this->postLineToGeneralLedger($general_ledger, $asset, $asset->depreciation_expense_account, $line->depreciation_amount, 0, "Depreciation expense - {$asset->asset_name}", $line->period_date);

        $this->postLineToGeneralLedger($general_ledger, $asset, $asset->accumulated_depreciation_account, 0, $line->depreciation_amount, "Accumulated depreciation - {$asset->asset_name}", $line->period_date);

        $line->update([
            'posted_checkbox' => true,
            'posted_on' => now(),
            'posted_by' => auth()->user()->id,
            'general_ledger_line_id' => $gl_line->id,
        ]);

        DB::commit();

        return $line;
    }

    /**
     * Dispose/write-off the asset: remove its cost and accumulated
     * depreciation from the books, recognize any gain or loss, and mark
     * the asset as disposed. Any remaining unposted schedule lines are
     * archived since they are no longer relevant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dispose(Request $request, $id)
    {
        $asset = FixedAsset::withTrashed()->findOrFail($id);

        if ($asset->asset_status == FixedAsset::STATUS_DISPOSED) {
            throw ValidationException::withMessages([
                'message' => ['This asset has already been disposed.']
            ]);
        }

        if (!$asset->main_account || !$asset->accumulated_depreciation_account) {
            throw ValidationException::withMessages([
                'message' => ['This asset is missing its asset or accumulated depreciation account.']
            ]);
        }

        $disposal_date = $request->filled('disposal_date') ? Carbon::parse($request->disposal_date) : now();
        $proceeds = round((float) $request->input('disposal_proceeds', 0), 2);
        $proceeds_account = $request->input('proceeds_account');

        if ($proceeds > 0 && !$proceeds_account) {
            throw ValidationException::withMessages([
                'message' => ['A proceeds account (e.g. Cash/Bank/Accounts Receivable) is required when disposal proceeds are greater than zero.']
            ]);
        }

        $accumulated_depreciation = $asset->getAccumulatedDepreciation();
        $book_value = (float) $asset->acquisition_cost - $accumulated_depreciation;
        $gain_loss = round($proceeds - $book_value, 2);

        if ($gain_loss != 0 && !$asset->gain_loss_account) {
            throw ValidationException::withMessages([
                'message' => ['A gain/loss on disposal account must be configured on this asset before it can be disposed with a non-zero gain or loss.']
            ]);
        }

        $general_ledger = $this->resolveGeneralLedger($asset->client_id, $disposal_date);

        DB::beginTransaction();

        if ($proceeds > 0) {
            $this->postLineToGeneralLedger($general_ledger, $asset, $proceeds_account, $proceeds, 0, "Disposal proceeds - {$asset->asset_name}", $disposal_date);
        }

        if ($accumulated_depreciation > 0) {
            $this->postLineToGeneralLedger($general_ledger, $asset, $asset->accumulated_depreciation_account, $accumulated_depreciation, 0, "Disposal - remove accumulated depreciation - {$asset->asset_name}", $disposal_date);
        }

        $this->postLineToGeneralLedger($general_ledger, $asset, $asset->main_account, 0, $asset->acquisition_cost, "Disposal - remove asset cost - {$asset->asset_name}", $disposal_date);

        if ($gain_loss != 0) {
            $this->postLineToGeneralLedger($general_ledger, $asset, $asset->gain_loss_account, $gain_loss < 0 ? abs($gain_loss) : 0, $gain_loss > 0 ? $gain_loss : 0, "Disposal - gain/(loss) - {$asset->asset_name}", $disposal_date);
        }

        $asset->update([
            'asset_status' => FixedAsset::STATUS_DISPOSED,
            'disposal_date' => $disposal_date,
            'disposal_proceeds' => $proceeds,
            'disposal_proceeds_account' => $proceeds_account,
            'disposal_gain_loss' => $gain_loss,
        ]);

        /* Any remaining unposted schedule lines are no longer relevant */
        FixedAssetDepreciationLine::where('asset_id', $asset->asset_id)->where('posted_checkbox', false)->get()->each(function ($line) {
            $line->archive();
        });

        DB::commit();

        return response()->json([
            'message' => "Asset disposed. Gain/(Loss) on disposal: " . number_format($gain_loss, 2),
        ]);
    }

    /**
     * Resolve the open Ledger + General Ledger for a client on a given date,
     * mirroring the lookup used when posting General Journal vouchers.
     */
    protected function resolveGeneralLedger($client_id, $date)
    {
        $login_user_company_id = auth()->user()->company_id;

        $ledger = Ledger::where('client_id', $client_id)->where('company_id', $login_user_company_id)->whereDate('active_from', '<=', $date)->whereDate('active_to', '>=', $date)->first();

        if (!$ledger) {
            throw ValidationException::withMessages([
                'message' => ['No available ledger found for ' . Carbon::parse($date)->format('m/d/Y') . '.']
            ]);
        }

        $general_ledger = $ledger->general_ledger()->whereDate('period_from', '<=', $date)->whereDate('period_to', '>=', $date)->first();

        if (!$general_ledger) {
            throw ValidationException::withMessages([
                'message' => ['No general ledger period found for ' . Carbon::parse($date)->format('m/d/Y') . '.']
            ]);
        }

        return $general_ledger;
    }

    /**
     * Insert a single General Ledger line, filling in the denormalized main
     * account fields the same way General Journal posting does.
     */
    protected function postLineToGeneralLedger($general_ledger, $asset, $main_account_id, $debit, $credit, $description, $date)
    {
        $main_account = MainAccount::find($main_account_id);

        if (!$main_account) {
            throw ValidationException::withMessages([
                'message' => ["Main account #{$main_account_id} used by this asset was not found."]
            ]);
        }

        $count = $general_ledger->general_ledger_lines->count() + 1;
        $number = str_pad($count, 4, '0', STR_PAD_LEFT);
        $code = 'FXDASST-' . now()->format('m-d-y') . '-' . $number;

        return $general_ledger->general_ledger_lines()->create([
            'journal_line_id' => $count,
            'ledger_journal_code' => $code,
            'ledger_journal_line_id' => $asset->asset_id,
            'ledger_line_number' => $number,
            'company_id' => auth()->user()->company_id,
            'client_id' => $asset->client_id,
            'journal_header_id' => $asset->asset_id,
            'journal_voucher_id' => $asset->asset_id,
            'journal_name' => 'Fixed Asset - ' . $asset->asset_name,
            'journal_type' => 'Fixed Asset',
            'description' => $description,
            'main_account_code_number' => $main_account->main_account_code_number,
            'main_account' => $main_account->id,
            'main_account_type' => $main_account->main_account_type,
            'main_account_category' => $main_account->main_account_category_id,
            'main_account_normal_balance' => $main_account->balance_control,
            'ledger_transaction_date' => $date,
            'cost_center' => '---',
            'department' => '---',
            'expense_purpose' => '---',
            'matched_voucher_to_gl' => 'All Matched',
            'debit_amount' => $debit,
            'credit_amount' => $credit,
            'balance_amount' => $debit - $credit,
            'posted_voucher' => 'Posted',
            'created_by' => auth()->user()->fullname,
        ]);
    }
}
