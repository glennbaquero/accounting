<?php

namespace App\Http\Controllers\RecurringJournalTemplates;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\RecurringJournalTemplates\RecurringJournalTemplate;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

use Carbon\Carbon;

class RecurringJournalTemplateFetchController extends Controller
{
    /**
     * Set object class of fetched data
     *
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new RecurringJournalTemplate;
    }

    /**
     * Custom filtering of query
     *
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        $query = $query->where('company_id', auth()->user()->company_id);

        return $query->withTrashed();
    }

    /**
     * Custom formatting of data
     *
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach ($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'client' => $item->client ? $item->client->name : '---',
                'template_id' => $item->template_id,
                'template_name' => $item->template_name,
                'journal_name' => $item->journal_name,
                'frequency' => $item->frequency,
                'next_run_date' => $item->next_run_date ? Carbon::parse($item->next_run_date)->format('m-d-Y') : '---',
                'occurrences_generated' => $item->occurrences_generated,
                'status' => $item->status,
                'created_at' => $item->renderDate(),
                'deleted_at' => $item->deleted_at,
            ]);

            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     *
     * @param  App\Models\RecurringJournalTemplates\RecurringJournalTemplate
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $mainaccounts = MainAccount::all();
        $client = User::getClients();

        if ($id) {
            $item = RecurringJournalTemplate::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'mainaccounts' => $mainaccounts,
            'clients' => $client,
        ]);
    }

    protected function formatView($item)
    {
        $item->created_by = $item->created_by_user;
        $item->updated_by = $item->updated_by_user;
        $item->formatted_created_at = $item->renderDate('created_at');
        $item->formatted_updated_at = $item->renderDate('updated_at');

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        $item->template_lines = $item->template_lines()->get();
        $item->runs = $item->runs()->orderByDesc('run_date')->get();

        return $item;
    }
}
