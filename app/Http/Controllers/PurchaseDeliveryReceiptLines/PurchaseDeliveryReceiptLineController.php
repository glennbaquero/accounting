<?php

namespace App\Http\Controllers\PurchaseDeliveryReceiptLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Invoices\PurchaseDeliveryReceiptLine;

class PurchaseDeliveryReceiptLineController extends Controller
{
    
   	/**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\PurchaseOrderLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = PurchaseDeliveryReceiptLine::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully remove the purchase order line {$item->purchase_delivery_receipt_line_number}",
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
   	    $item = PurchaseDeliveryReceiptLine::withTrashed()->findOrFail($id);
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
          $item = PurchaseDeliveryReceiptLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Approved';
          $item->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          $vendor_line = PurchaseDeliveryReceiptLine::withTrashed()->where('purchase_delivery_receipt_line_number', $item->purchase_delivery_receipt_line_number)->first();
          
          $vendor_line->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          return response()->json([
              'message' => "You have successfully approved {$item->purchase_delivery_receipt_line_number}",
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
          $item = PurchaseDeliveryReceiptLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Rejected';

          $item->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);

          $vendor_line = PurchaseDeliveryReceiptLine::withTrashed()->where('purchase_delivery_receipt_line_number', $item->purchase_delivery_receipt_line_number)->first();
          $vendor_line->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);


          return response()->json([
              'message' => "You have successfully rejected {$item->purchase_delivery_receipt_line_number}",
          ]);
      }
}
