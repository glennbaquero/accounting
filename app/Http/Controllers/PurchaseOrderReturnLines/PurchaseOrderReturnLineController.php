<?php

namespace App\Http\Controllers\PurchaseOrderReturnLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PurchaseOrders\PurchaseOrderReturnLine;
use App\Models\Invoices\VendorInvoiceLine;

class PurchaseOrderReturnLineController extends Controller
{
    
   	/**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\PurchaseOrderReturnLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = PurchaseOrderReturnLine::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully remove the purchase order return line {$item->return_line_number}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\PurchaseOrderReturnLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = PurchaseOrderReturnLine::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->id}",
   	    ]);
   	}

      /**
       * Approve the specified resource from storage.
       *
       * @param  \App\PurchaseOrderReturnLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function approve($id)
      {
          $item = PurchaseOrderReturnLine::withTrashed()->findOrFail($id);
          $item->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          $vendor_line = VendorInvoiceLine::withTrashed()->where('purchase_order_line_number', $item->return_line_number)->first();
          $vendor_line->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          return response()->json([
              'message' => "You have successfully approved {$item->return_line_number}",
          ]);
      }

      /**
       * Reject the specified resource from storage.
       *
       * @param  \App\PurchaseOrderReturnLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function reject($id)
      {
          $item = PurchaseOrderReturnLine::withTrashed()->findOrFail($id);
          $item->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);

          $vendor_line = VendorInvoiceLine::withTrashed()->where('purchase_order_line_number', $item->return_line_number)->first();
          $vendor_line->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);


          return response()->json([
              'message' => "You have successfully rejected {$item->return_line_number}",
          ]);
      }
}
