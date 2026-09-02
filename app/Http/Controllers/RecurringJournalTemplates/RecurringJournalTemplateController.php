<?php

namespace App\Http\Controllers\RecurringJournalTemplates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\RecurringJournalTemplates\RecurringJournalTemplateStoreRequest;
use App\Models\RecurringJournalTemplates\RecurringJournalTemplate;
use App\Models\RecurringJournalTemplateLines\RecurringJournalTemplateLine;

use App\Models\Users\User;

class RecurringJournalTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('recurring-journal-templates.index', [
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
        $clients = User::getClients();
        $count = RecurringJournalTemplate::withTrashed()->count() + 1;
        $template_id = 'RJT-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        return view('recurring-journal-templates.create', [
            'clients' => $clients,
            'template_id' => $template_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RecurringJournalTemplates\RecurringJournalTemplateStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecurringJournalTemplateStoreRequest $request)
    {
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = RecurringJournalTemplate::store($request);

        $this->syncLines($item, $request->input('template_lines'));

        $message = "You have successfully created {$item->template_name}";
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
        $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);

        return view('recurring-journal-templates.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RecurringJournalTemplates\RecurringJournalTemplateStoreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecurringJournalTemplateStoreRequest $request, $id)
    {
        $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->template_name}";
        $request['updated_by'] = auth()->user()->id;

        $item = RecurringJournalTemplate::store($request, $item);

        $this->syncLines($item, $request->input('template_lines'));

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
        $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->template_name}",
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
        $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->template_name}",
        ]);
    }

    /**
     * Pause an active template so the scheduler skips it.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pause($id)
    {
        $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);

        if ($item->status !== RecurringJournalTemplate::STATUS_ACTIVE) {
            throw ValidationException::withMessages([
                'message' => ["Only an active template can be paused."]
            ]);
        }

        $item->update(['status' => RecurringJournalTemplate::STATUS_PAUSED]);

        return response()->json([
            'message' => "{$item->template_name} has been paused.",
        ]);
    }

    /**
     * Resume a paused template.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resume($id)
    {
        $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);

        if ($item->status !== RecurringJournalTemplate::STATUS_PAUSED) {
            throw ValidationException::withMessages([
                'message' => ["Only a paused template can be resumed."]
            ]);
        }

        $item->update(['status' => RecurringJournalTemplate::STATUS_ACTIVE]);

        return response()->json([
            'message' => "{$item->template_name} has been resumed.",
        ]);
    }

    /**
     * Manually generate a journal from this template right now, regardless
     * of its next_run_date - useful for testing a template before relying
     * on the scheduler.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function runNow($id)
    {
        $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);

        if (!$item->template_lines()->count()) {
            throw ValidationException::withMessages([
                'message' => ["Add at least one line before generating a journal."]
            ]);
        }

        $journal = $item->generateJournalEntry();

        return response()->json([
            'message' => "Generated journal {$journal->general_journal_number} from {$item->template_name}.",
        ]);
    }

    /**
     * Create/update/delete template lines submitted as a single JSON
     * payload (`template_lines`) alongside the header.
     *
     * @param  \App\Models\RecurringJournalTemplates\RecurringJournalTemplate  $item
     * @param  string|null  $linesPayload
     * @return void
     */
    protected function syncLines($item, $linesPayload)
    {
        if (!$linesPayload) {
            return;
        }

        $lines = is_array($linesPayload) ? $linesPayload : json_decode($linesPayload, true);

        if (!is_array($lines)) {
            return;
        }

        $submittedIds = [];

        foreach ($lines as $line) {
            $line['template_id'] = $item->template_id;
            $line['client_id'] = $item->client_id;
            $line['created_by'] = $line['created_by'] ?? auth()->user()->id;
            $line['updated_by'] = auth()->user()->id;

            $existing = !empty($line['id']) ? RecurringJournalTemplateLine::where('template_id', $item->template_id)->where('id', $line['id'])->first() : null;

            $templateLine = RecurringJournalTemplateLine::store($line, $existing);

            $submittedIds[] = $templateLine->id;
        }

        RecurringJournalTemplateLine::where('template_id', $item->template_id)->whereNotIn('id', $submittedIds)->get()->each(function ($line) {
            $line->archive();
        });
    }
}
