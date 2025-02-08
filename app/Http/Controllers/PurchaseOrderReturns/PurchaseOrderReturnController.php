<?php

namespace App\Http\Controllers\PurchaseOrderReturns;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\PurchaseOrders\PurchaseOrderReturnStoreRequest;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;
use App\Models\PurchaseOrders\PurchaseOrderReturn;
use App\Models\PurchaseOrders\PurchaseOrderReturnLine;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use PDF;

class PurchaseOrderReturnController extends Controller
{
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{	
   	    return view('purchase-order-returns.index', [
   	        'clients' => User::getClients(),
   	    ]);
   	}

   	/**
   	 * Show the form for creating a new resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function create()
   	{
         $purchase_orders = PurchaseOrderReturn::withTrashed()->count();
         $order_number = str_pad($purchase_orders, 4, '0', STR_PAD_LEFT);
         return view('purchase-order-returns.create', [
   	        'order_number' => $order_number
   	   ]);
   	}

   	/**
   	 * Store a newly created resource in storage.
   	 *
   	 * @param  \Illuminate\Http\PurchaseOrderReturnStoreRequest  $request
   	 * @return \Illuminate\Http\Response
   	 */
   	public function store(PurchaseOrderReturnStoreRequest $request)
   	{	
		if(!$request['purchase_order_lines']) {
           throw ValidationException::withMessages(['purchase order return lines' => 'Please add purchase order return lines']);
        }

        $code = DocumentCodeControl::generateCode($request->client_id, 1, 'App\Models\PurchaseOrders\PurchaseOrderReturn');

        if($code) {
            $request['purchase_order_return_number'] = $code;
        }

		
		DB::beginTransaction();

		try {

			$request['created_by'] = auth()->user()->id;
			$request['updated_by'] = auth()->user()->id;

			$item = PurchaseOrderReturn::store($request);
			$lines = $request->purchase_order_lines;
			if(isset($lines)) {
				foreach($lines as $line) {
				if(isset($line['is_new'])) {
					$purchase_orders = PurchaseOrderReturnLine::withTrashed()->count();
         			$order_number = str_pad($purchase_orders, 4, '0', STR_PAD_LEFT);

					$purchase_line = $item->purchase_order_return_lines()->create([
					'return_line_number' => $line['return_line_number'] ?? 'PORL-'.$order_number,	
					'vendor_account' => $item->vendor_account, 
					'invoice_account' => $item->invoice_account, 
					'line_number' => $item->id, 
					'line_status' => $line['line_status'], 
					'procurement_category' => $line['procurement_category'], 

					// Product Information
					'product_number' => $line['product']['product_number'], 
					'product_name' => $line['product']['name'],
					'batch_number' => $line['product']['batch_number'], 
					'serial_number' => $line['product']['serial_number'],

					// Product Raw Data
					'product' => $line['product'],

					// Variant Information
					'variant_number' => $line['variant']['variant_number'], 
					'variant_name' => $line['variant']['name'], 
					'unit_price' => $line['variant']['unit_price'],

					// Variant Raw Data
					'variant' => $line['variant'],

					// Cost Information
					'quantity' => $line['quantity'], 
					'amount' => $line['amount'], 
					'discount' => $line['discount'],
					'discount_percentage' => $line['discount_percentage'],

					// Financial Dimension
					'sales_tax_group' => $item['sales_tax_group'],
					'cost_center_id' => $line['cost_center_id'], 
					'department_id' => $line['department_id'], 
					'expense_purpose_id' => $line['expense_purpose_id'], 

					'product_id' => $line['product_id'], 
					'variant_id' => $line['variant_id'], 
					'charge_on_purchase' => $line['charge_on_purchase'],
					'reason' => $line['reason'],

					'procurement_id' => $line['procurement_id'],
					'specification_id' => $line['specification_id'],

					'service_id' => $line['service_id'],
					'service_task' => $line['service_task'],
					'service_task_details' => $line['service_task_details'],
					'rpm_method' => $line['rpm_method'],
					'number_of_hours' => $line['number_of_hours'],

					'specification_id' => $line['specification_id'],
					'specification_id' => $line['specification_id'],
					'charge_id' => $line['charge_id'],

					'discount_id' => $line['discount_id'],

					'less_discount' => $line['less_discount'],

					'cash_discount' => $line['cash_discount'],

					'add_charge' => $line['add_charge'],

					'charge' => $line['charge'],

					'add_fee' => $line['add_fee'],

					'fee' => $line['fee'],

					'line_amount' => $line['line_amount'],

					'additional_tax' => $line['additional_tax'],

					'vat_amount' => $line['vat_amount'],

					'line_vat' => $line['line_vat'],

					'total_sales_vat_inclusive' => $line['total_sales_vat_inclusive'],

					// Audit Information
					'created_by' => auth()->user()->id, 
					'updated_by' => auth()->user()->id, 
					]);
					}
					
				}
			}


		}catch (\Throwable $e) {
			DB::rollback();
			throw $e;
		}

		DB::commit();

   	    $message = "You have successfully created {$item->purchase_order_return_number}";
   	    $redirect = $item->renderShowUrl();

   	    return response()->json([
   	        'message' => $message,
   	        'redirect' => $redirect,
   	    ]);
   	}

   	/**
   	 * Display the specified resource.
   	 *
   	 * @param  \App\PurchaseOrder  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function show($id)
   	{
   	    $item = PurchaseOrderReturn::withTrashed()->findOrFail($id);

   	    return view('purchase-order-returns.show', [
   	        'item' => $item,
   	    ]);
   	}

   	/**
   	 * Update the specified resource in storage.
   	 *
   	 * @param  \Illuminate\Http\PurchaseOrderReturnStoreRequest  $request
   	 * @param  \App\PurchaseOrderReturn  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function update(PurchaseOrderReturnStoreRequest $request, $id)
   	{
		if(!$request['purchase_order_return_lines']) {
			throw ValidationException::withMessages(['purchase order return lines' => 'Please add purchase order return lines']);
		}

		
	
   	    $item = PurchaseOrderReturn::withTrashed()->findOrFail($id);
   	    $message = "You have successfully updated {$item->purchase_order_return_number}";
   		$request['updated_by'] = auth()->user()->id;
		$request['created_by'] =  $item->created_by;
   	    $item = PurchaseOrderReturn::store($request, $item);

   	    $lines = $request->purchase_order_lines;

   	    if(isset($lines)) {
   	    	foreach($lines as $line) {
   	    		if(isset($line['is_new'])) {
   	    			$item->purchase_order_return_lines()->create([
						'return_line_number' => $line['return_line_number'],	
						'vendor_account' => $item->vendor_account, 
						'invoice_account' => $item->invoice_account, 
						'line_number' => $item->id, 
						'line_status' => $line['line_status'], 
						'procurement_category' => $line['procurement_category'], 
	
						// Product Information
						'product_number' => $line['product']['product_number'], 
						'product_name' => $line['product']['name'],
						'batch_number' => $line['product']['batch_number'], 
						'serial_number' => $line['product']['serial_number'],
	
						// Product Raw Data
						'product' => $line['product'],
	
						// Variant Information
						'variant_number' => $line['variant']['variant_number'], 
						'variant_name' => $line['variant']['name'], 
						'unit_price' => $line['variant']['unit_price'],
	
						// Variant Raw Data
						'variant' => $line['variant'],
	
						// Cost Information
						'quantity' => $line['quantity'], 
						'amount' => $line['amount'], 
						'discount' => $line['discount'],
						'discount_percentage' => $line['discount_percentage'],
						'reason' => $line['reason'],
	
						// Financial Dimension
						'sales_tax_group' => $item['sales_tax_group'],
						'cost_center_id' => $line['cost_center_id'], 
						'department_id' => $line['department_id'], 
						'expense_purpose_id' => $line['expense_purpose_id'], 

						'product_id' => $line['product_id'], 
						'variant_id' => $line['variant_id'],
						'charge_on_purchase' => $line['charge_on_purchase'],

						'procurement_id' => $line['procurement_id'],
						'specification_id' => $line['specification_id'],

						'service_id' => $line['service_id'],
						'service_task' => $line['service_task'],
						'service_task_details' => $line['service_task_details'],
						'rpm_method' => $line['rpm_method'],
						'number_of_hours' => $line['number_of_hours'],

						'specification_id' => $line['specification_id'],
						'specification_id' => $line['specification_id'],
						'charge_id' => $line['charge_id'],

						'discount_id' => $line['discount_id'],
						'less_discount' => $line['less_discount'],

						'cash_discount' => $line['cash_discount'],

						'add_charge' => $line['add_charge'],

						'charge' => $line['charge'],

						'add_fee' => $line['add_fee'],

						'fee' => $line['fee'],

						'line_amount' => $line['line_amount'],

						'additional_tax' => $line['additional_tax'],

						'vat_amount' => $line['vat_amount'],

						'line_vat' => $line['line_vat'],

						'total_sales_vat_inclusive' => $line['total_sales_vat_inclusive'],

						// Audit Information
						'created_by' => auth()->user()->id, 
						'updated_by' => auth()->user()->id, 
   	    			]);
   	    		} else {
					
					PurchaseOrderLine::findOrFail($line['id'])->update([
						'vendor_account' => $item->vendor_account, 
						'invoice_account' => $item->invoice_account, 
						'line_number' => $item->id, 
						'line_status' => $line['line_status'], 
						'procurement_category' => $line['procurement_category'], 
	
						// Product Information
						'product_number' => $line['product']['product_number'], 
						'product_name' => $line['product']['name'],
						'batch_number' => $line['product']['batch_number'], 
						'serial_number' => $line['product']['serial_number'],
	
						// Product Raw Data
						'product' => $line['product'],
	
						// Variant Information
						'variant_number' => $line['variant']['variant_number'], 
						'variant_name' => $line['variant']['name'], 
						'unit_price' => $line['variant']['unit_price'],
	
						// Variant Raw Data
						'variant' => $line['variant'],
	
						// Cost Information
						'quantity' => $line['quantity'], 
						'amount' => $line['amount'], 
						'discount' => $line['discount'],
						'discount_percentage' => $line['discount_percentage'],
	
						// Financial Dimension
						'sales_tax_group' => $item['sales_tax_group'],
						'cost_center_id' => $line['cost_center_id'], 
						'department_id' => $line['department_id'], 
						'expense_purpose_id' => $line['expense_purpose_id'],

						'charge_on_purchase' => $line['charge_on_purchase'],
						'reason' => $line['reason'],

						'procurement_id' => $line['procurement_id'],
						'specification_id' => $line['specification_id'],

						'service_id' => $line['service_id'],
						'service_task' => $line['service_task'],
						'service_task_details' => $line['service_task_details'],
						'rpm_method' => $line['rpm_method'],
						'number_of_hours' => $line['number_of_hours'],

						'charge_id' => $line['charge_id'],

						'discount_id' => $line['discount_id'],

						'product_id' => $line['product_id'], 
						'variant_id' => $line['variant_id'], 
						'less_discount' => $line['less_discount'],

						'cash_discount' => $line['cash_discount'],

						'add_charge' => $line['add_charge'],

						'charge' => $line['charge'],

						'add_fee' => $line['add_fee'],

						'fee' => $line['fee'],

						'line_amount' => $line['line_amount'],

						'additional_tax' => $line['additional_tax'],

						'vat_amount' => $line['vat_amount'],

						'line_vat' => $line['line_vat'],

						'total_sales_vat_inclusive' => $line['total_sales_vat_inclusive'],

						// Audit Information
						'updated_by' => auth()->user()->id, 
					]);
   	    		}
   	    	}
   	    }
   	    

   	    return response()->json([
   	        'message' => $message,
   	    ]);
   	}

   	/**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\PurchaseOrderReturn  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = PurchaseOrderReturn::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully archived {$item->purchase_order_return_number}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\PurchaseOrderReturn  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = PurchaseOrderReturn::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->purchase_order_return_number}",
   	    ]);
   	}

      /**
       * Confirm the specified resource in storage.
       *
       * @param  \Illuminate\Http\PurchaseOrderReturnStoreRequest  $request
       * @param  \App\PurchaseOrderReturn  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function confirmation(Request $request, $id)
      {
         $item = PurchaseOrderReturn::withTrashed()->findOrFail($id);
         $message = "You have successfully confirm the {$item->purchase_order_return_number}";

         $item->update([
            'confirmed_by' => auth()->user()->id,
            'confirmed_date' => now(),
            'approver' => auth()->user()->id,
            'approval_status_date' => now(),
         ]);

         return response()->json([
              'message' => $message,
         ]);
      }

	public function printPDF($id)
	{
		$po = PurchaseOrderReturn::find($id);

		$data = [
			'po' => $po,
			'vendor' => $po,
		];

	
		return view('purchase-order-returns.print',[
			'po' => $po,
			'vendor' => $po->vendor,
			'po_lines' => $po->purchase_order_return_lines,
		]);
	}

	public function cancel(Request $request, $id)
	{
		$po = PurchaseOrderReturn::find($id);

		$po->update([
			'is_cancelled' => true,
			'cancelled_on' => now(),
			'cancelled_by' => auth()->user()->id,
		]);
	
		return view('purchase-order-returns.print',[
			'po' => $po,
			'vendor' => $po->vendor,
			'po_lines' => $po->purchase_order_return_lines,
		]);
	}
	  
}
