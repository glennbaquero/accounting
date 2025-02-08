<?php

namespace App\Http\Controllers\CustomerInvoiceLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoices\CustomerInvoiceLine;

class CustomerInvoiceLineController extends Controller
{
    /**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\CustomerInvoiceLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = CustomerInvoiceLine::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully remove the customer invoice line {$item->customer_invoice_line_number}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\CustomerInvoiceLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = CustomerInvoiceLine::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->id}",
   	    ]);
   	}

      /**
       * Approve the specified resource from storage.
       *
       * @param  \App\CustomerInvoiceLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function approve($id)
      {
          $item = CustomerInvoiceLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Approved';
          $item->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          return response()->json([
              'message' => "You have successfully approved {$item->customer_invoice_line_number}",
          ]);
      }

      /**
       * Reject the specified resource from storage.
       *
       * @param  \App\CustomerInvoiceLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function reject($id)
      {
          $item = CustomerInvoiceLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Rejected';
          $item->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);

          return response()->json([
              'message' => "You have successfully rejected {$item->customer_invoice_line_number}",
          ]);
      }
}
