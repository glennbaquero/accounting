<?php

namespace App\Http\Controllers\PaymentSchedules;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\PaymentSchedules\PaymentScheduleLine;

use Carbon\Carbon;

class PaymentScheduleLineFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new PaymentScheduleLine;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        /**
         * Queries
         * 
         */
        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        if($this->request->filled('payment_schedule_id')) {
            $query = $query->where('payment_schedule_id', $this->request->payment_schedule_id);
        }

        $query = $query->where('company_id', auth()->user()->company_id);
        
        return $query;
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

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'schedule_line_id' => $item->schedule_line_id,
                'payment_schedule_id' => $item->payment_schedule_id,
                'due_date' => $item->due_date,
                'duration' => $item->duration,
                'principal_amount' => $item->principal_amount,
                'interest' => $item->interest,
                'payment' => $item->payment,
                'balance' => $item->balance,
                'line_status' => $item->line_status,
                'created_at' => $item->renderDate('created_at', 'm/d/Y h:i A'),
                
                'deleted_at' => $item->deleted_at,
            ]);

            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     * 
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'updateUrl' => $item->renderUpdateUrl(),
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        if ($id) {
            $item = PaymentScheduleLine::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
