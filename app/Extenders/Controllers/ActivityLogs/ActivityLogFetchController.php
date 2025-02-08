<?php

namespace App\Extenders\Controllers\ActivityLogs;

use Illuminate\Http\Request;
use App\Extenders\Controllers\FetchController as Controller;

use App\Models\ActivityLogs\ActivityLog;

use App\Models\Pages\Page;
use App\Models\Pages\MetaTag;

class ActivityLogFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass() 
    {
        $this->class = new ActivityLog;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query) 
    {
        $query = $this->additionalQuery($query);

        $query = $this->filterSubject($query, 'payment-methods', 'App\Models\JournalSetups\PaymentMethod');
        $query = $this->filterSubject($query, 'products', 'App\Models\JournalSetups\Product');
        $query = $this->filterSubject($query, 'vendors', 'App\Models\Vendors\Vendor');
        $query = $this->filterSubject($query, 'terms', 'App\Models\JournalSetups\TermsOfPayment');
        $query = $this->filterSubject($query, 'purchase-orders', 'App\Models\PurchaseOrders\PurchaseOrder');
        $query = $this->filterSubject($query, 'purchase-order-lines', 'App\Models\PurchaseOrders\PurchaseOrderLine');
        $query = $this->filterSubject($query, 'customers', 'App\Models\Customers\Customer');

        return $query;
    }

    /**
     * Filter Subject
     * @param  QueryBuilder $query   
     * @param  string $param  
     * @param  string $subject 
     * @return Query Builder          
     */
    protected function filterSubject($query, $param, $subject, $id = false) 
    {
        if ($this->request->filled($param)) {
            $filters = [
                'subject_type' => $subject,
            ];

            if ($id) {
                $filters = array_merge($filters, [
                    'subject_id' => $id,
                ]);
            } else {
                if ($this->request->filled('id')) {
                    $filters = array_merge($filters, [
                        'subject_id' => $this->request->input('id'),
                    ]);
                }
            }

            $query = $query->where($filters);
        }

        return $query;
    }

    /**
     * Additional Query for when being extended
     * @param  QueryBuilder $query
     * @return QueryBuilder
     */
    public function additionalQuery($query) 
    {
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

            array_push($result, array_merge($data, [
                'id' => $item->id,
                'name' => $item->renderName(),
                'caused_by' => $item->renderCauserName(),
                'show_causer' => $item->renderCauserShowUrl(),
                'subject_type' => $item->renderSubjectType(),
                'subject_name' => $item->renderSubjectName(),
                'created_at' => $item->renderDate(),
            ]));
        }

        return $result;
    }

    /**
     * Additional property when extended
     * 
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item) 
    {
        return [
            'showUrl' => $item->renderShowUrl(),
        ];
    }
}
