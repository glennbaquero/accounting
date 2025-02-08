<?php

namespace App\Http\Controllers\SalesOrders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\SalesOrders\SalesOrderStoreRequest;

use App\Models\SalesOrders\SalesOrder;
use App\Models\SalesOrders\SalesOrderLine;
use App\Models\Invoices\CustomerInvoice;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;
use Throwable;
use PDF;

class SalesOrderController extends Controller
{
    
   	/**
   	 * Display a listing of the resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function index()
   	{
   	    return view('sales-orders.index', [
            'clients' => User::getClients()
   	    ]);
   	}

   	/**
   	 * Show the form for creating a new resource.
   	 *
   	 * @return \Illuminate\Http\Response
   	 */
   	public function create()
   	{
         $sales_orders = SalesOrder::count();
         $order_number = str_pad($sales_orders, 4, '0', STR_PAD_LEFT);
         return view('sales-orders.create', [
            'order_number' => $order_number
   	   ]);
   	}

   	/**
   	 * Store a newly created resource in storage.
   	 *
   	 * @param  \Illuminate\Http\SalesOrderStoreRequest  $request
   	 * @return \Illuminate\Http\Response
   	 */
   	public function store(SalesOrderStoreRequest $request)
   	{	
		$code = DocumentCodeControl::generateCode($request->client_id,2, 'App\Models\Invoices\VendorInvoice');

        if($code) {
            $request['sales_order_number'] = $code;
        }

		if(!$request['sales_order_lines']) {
			throw ValidationException::withMessages(['sales order lines' => 'Please add sales order lines']);
		}

		try {
			DB::beginTransaction();

			if(!$request->filled('sales_order_number')) {
				$request['sales_order_number'] = uniqid();
			}
			
			$request['created_by'] = auth()->user()->id;
			$request['updated_by'] = null;

			$item = SalesOrder::store($request);

			$lines = $request->sales_order_lines;
			if(isset($lines)) {
				foreach($lines as $line) {
					if(isset($line['is_new'])) {
						$sales_line = $item->sales_order_lines()->create([
							'sales_order_line_number' => $line['sales_order_line_number'],
							'customer_account' => $item->customer_account, 
							'invoice_account' => $item->invoice_account, 
							'line_number' => $item->id, 
							'line_status' => $line['line_status'], 
							'sales_category' => $line['sales_category'], 

							// Product Information
							'item_number' => $line['product']['product_number'],
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
							'service_id' => $line['service_id'],
							'service_task' => $line['service_task'],
							'service_task_details' => $line['service_task_details'],
							'rpm_method' => $line['rpm_method'],
							'number_of_hours' => $line['number_of_hours'],

							'procurement_id' => $line['procurement_id'],
							'specification_id' => $line['specification_id'],

							'charge_id' => $line['charge_id'],
							'discount_id' => $line['discount_id'],

							'company_id' => $item->company_id,
							'client_id' => $item->client_id,

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
						]);
					}
				}
			}

			$message = "You have successfully created {$item->sales_order_number}";
			$redirect = $item->renderShowUrl();
			DB::commit();
			return response()->json([
				'message' => $message,
				'redirect' => $redirect,
			]);
		} catch (Throwable $e) {
			DB::rollback();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
		}
   	}

   	/**
   	 * Display the specified resource.
   	 *
   	 * @param  \App\SalesOrder  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function show($id)
   	{
   	    $item = SalesOrder::withTrashed()->findOrFail($id);

   	    return view('sales-orders.show', [
   	        'item' => $item,
   	    ]);
   	}

   	/**
   	 * Update the specified resource in storage.
   	 *
   	 * @param  \Illuminate\Http\SalesOrderStoreRequest  $request
   	 * @param  \App\SalesOrder  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function update(SalesOrderStoreRequest $request, $id)
   	{
         if(!$request['sales_order_lines']) {
            throw ValidationException::withMessages(['sales order lines' => 'Please add sales order lines']);
         }
         
         $item = SalesOrder::withTrashed()->findOrFail($id);
         $message = "You have successfully updated {$item->sales_order_number}";
         $request['updated_by'] = auth()->user()->id;
         $request['updated_at'] = now();
         $request['created_by'] =  $item->created_by;

         $item = SalesOrder::store($request, $item);

         if($item->customer_invoice) {
            $item->customer_invoice->store($request, $item->customer_invoice, true);
         }
         

         $lines = $request->sales_order_lines;

   	   if(isset($lines)) {
   	    	foreach($lines as $line) {
   	    		if(isset($line['is_new'])) {
					$sales_line = $item->sales_order_lines()->create([
						'sales_order_line_number' => $line['sales_order_line_number'],
						'customer_account' => $item->customer_account, 
						'invoice_account' => $item->invoice_account, 
						'line_number' => $item->id, 
						'line_status' => $line['line_status'], 
						'sales_category' => $line['sales_category'], 

						// Product Information
						'item_number' => $line['product']['product_number'],
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
						
						'procurement_id' => $line['procurement_id'],
						'specification_id' => $line['specification_id'],

						'service_id' => $line['service_id'],
						'service_task' => $line['service_task'],
						'service_task_details' => $line['service_task_details'],
						'rpm_method' => $line['rpm_method'],
						'number_of_hours' => $line['number_of_hours'],

						'charge_id' => $line['charge_id'],

						'discount_id' => $line['discount_id'],

						'company_id' => $item->company_id,
						'client_id' => $item->client_id,

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
					]);
   	    		} else {
   	    			SalesOrderLine::findOrFail($line['id'])->update([
						'sales_order_line_number' => $line['sales_order_line_number'],
						'customer_account' => $item->customer_account, 
						'invoice_account' => $item->invoice_account, 
						'line_number' => $item->id, 
						'line_status' => $line['line_status'], 
						'sales_category' => $line['sales_category'], 

						// Product Information
						'item_number' => $line['product']['product_number'],
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

						'procurement_id' => $line['procurement_id'],
						'specification_id' => $line['specification_id'],
						
						'service_id' => $line['service_id'],
						'service_task' => $line['service_task'],
						'service_task_details' => $line['service_task_details'],
						'rpm_method' => $line['rpm_method'],
						'number_of_hours' => $line['number_of_hours'],

						'charge_id' => $line['charge_id'],
						
						'discount_id' => $line['discount_id'],

						'company_id' => $item->company_id,
						'client_id' => $item->client_id,

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
   	 * @param  \App\SalesOrder  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = SalesOrder::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully archived {$item->sales_order_number}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\SalesOrder  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = SalesOrder::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->sales_order_number}",
   	    ]);
   	}

      /**
       * Confirm the specified resource in storage.
       *
       * @param  \Illuminate\Http\SalesOrderStoreRequest  $request
       * @param  \App\SalesOrder  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function confirmation(Request $request, $id)
      {
         $item = SalesOrder::withTrashed()->findOrFail($id);
         $message = "You have successfully confirm the {$item->sales_order_number}";

         $item->update([
            'confirmed_by' => auth()->user()->id,
            'approver' => auth()->user()->id,
            'approval_status_date' => now(),
            'confirmed_date' => now()
         ]);

         return response()->json([
              'message' => $message,
         ]);
      }

    public function printPDF($id)
   	{
   		$so = SalesOrder::find($id);
   	
   		return view('sales-orders.print',[
   			'so' => $so,
   			'customer' => $so->customer,
   			'so_lines' => $so->sales_order_lines,
   		]);
   	}
}
