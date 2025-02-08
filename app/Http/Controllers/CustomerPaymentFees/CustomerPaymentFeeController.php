<?php

namespace App\Http\Controllers\CustomerPaymentFees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use Throwable;
use Carbon\Carbon;

use App\Models\Customers\CustomerPaymentFee;

class CustomerPaymentFeeController extends Controller
{
    public function index() {
        return view('customer-payment-fees.index', [
            //
        ]);
    }

    public function create($customer_invoice = null){ 

        $code = $customer_invoice;

        return view('customer-payment-fees.create', [
            //
        ]);
    }

    public function store(Request $request) {
    
        DB::beginTransaction();
        $item = CustomerPaymentFee::store($request);

        $message = "You have successfully created {$item->fee_id}";
        $redirect = $item->renderShowUrl();

        DB::commit();
      
        return [
            'message' => $message,
            'redirect' => $redirect
        ];
    }

    public function show($id) {
        $item = CustomerPaymentFee::withTrashed()->findOrFail($id);
        return view('customer-payment-fees.show', [
            'item' => $item
        ]);
    }

    public function update(Request $request, $id) {

        DB::beginTransaction();
        
        $item = CustomerPaymentFee::withTrashed()->findOrFail($id);
        $item = CustomerPaymentFee::store($request, $item);

        $message = "You have successfully updated {$item->fee_id}";

        DB::commit();
        
        return [ 'message' => $message ];
       
    }

    public function archive($id)
    {
        $item = CustomerPaymentFee::withTrashed()->findOrFail($id);
        $item->archive();

        return [
            'message' => "You have successfully archived {$item->fee_id}"
        ];
    }

    public function restore($id)
    {
        $item = CustomerPaymentFee::withTrashed()->findOrFail($id);
        $item->unarchive();

        return [
            'message' => "You have successfully restored {$item->fee_id}"
        ];
    }
}
