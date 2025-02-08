<?php

namespace App\Http\Controllers\VendorInvoiceLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Invoices\VendorInvoiceLine;

class VendorInvoiceLineController extends Controller
{
    
   	/**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\PurchaseOrderLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = VendorInvoiceLine::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully remove the purchase order line {$item->vendor_invoicer_line_number}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\PurchaseOrderLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = VendorInvoiceLine::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->id}",
   	    ]);
   	}

      /**
       * Approve the specified resource from storage.
       *
       * @param  \App\PurchaseOrderLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function approve($id)
      {
          $item = VendorInvoiceLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Approved';
          $item->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          $vendor_line = VendorInvoiceLine::withTrashed()->where('vendor_invoice_line_number', $item->vendor_invoice_line_number)->first();
          
          $vendor_line->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          return response()->json([
              'message' => "You have successfully approved {$item->vendor_invoice_line_number}",
          ]);
      }

      /**
       * Reject the specified resource from storage.
       *
       * @param  \App\PurchaseOrderLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function reject($id)
      {
          $item = VendorInvoiceLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Rejected';

          $item->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);

          $vendor_line = VendorInvoiceLine::withTrashed()->where('vendor_invoice_line_number', $item->vendor_invoice_line_number)->first();
          $vendor_line->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);


          return response()->json([
              'message' => "You have successfully rejected {$item->vendor_invoice_line_number}",
          ]);
      }
}
