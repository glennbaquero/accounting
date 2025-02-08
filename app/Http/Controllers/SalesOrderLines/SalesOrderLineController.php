<?php

namespace App\Http\Controllers\SalesOrderLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SalesOrders\SalesOrderLine;
use App\Models\Invoices\CustomerInvoiceLine;

class SalesOrderLineController extends Controller
{
    
   	/**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\SalesOrderLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = SalesOrderLine::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully remove the sales order line {$item->sales_order_line_number}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\SalesOrderLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = SalesOrderLine::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->id}",
   	    ]);
   	}

      /**
       * Approve the specified resource from storage.
       *
       * @param  \App\SalesOrderLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function approve($id)
      {
          $item = SalesOrderLine::withTrashed()->findOrFail($id);
          $item->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          $customer_invoice_line = CustomerInvoiceLine::withTrashed()->where('sales_order_line_number', $item->sales_order_line_number)->first();

          if($customer_invoice_line) {
            $customer_invoice_line->update([
              'approved_on' => now(),
              'rejected_on' => null
            ]);
          }

          return response()->json([
              'message' => "You have successfully approved {$item->sales_order_line_number}",
          ]);
      }

      /**
       * Reject the specified resource from storage.
       *
       * @param  \App\SalesOrderLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function reject($id)
      {
          $item = SalesOrderLine::withTrashed()->findOrFail($id);
          $item->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);

          $customer_invoice_line = CustomerInvoiceLine::withTrashed()->where('sales_order_line_number', $item->sales_order_line_number)->first();

          if($customer_invoice_line) {
            $customer_invoice_line->update([
              'rejected_on' => now(),
              'approved_on' => null,
            ]);
          }
         

          return response()->json([
              'message' => "You have successfully rejected {$item->sales_order_line_number}",
          ]);
      }
}
