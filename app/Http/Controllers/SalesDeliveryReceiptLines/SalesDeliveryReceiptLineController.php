<?php

namespace App\Http\Controllers\SalesDeliveryReceiptLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoices\SalesDeliveryReceiptLine;

class SalesDeliveryReceiptLineController extends Controller
{
    /**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param  \App\SalesDeliveryReceiptLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function archive($id)
   	{
   	    $item = SalesDeliveryReceiptLine::withTrashed()->findOrFail($id);
   	    $item->archive();

   	    return response()->json([
   	        'message' => "You have successfully remove the sales delivery receipt line {$item->sales_delivery_receipt_line_number}",
   	    ]);
   	}

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param  \App\SalesDeliveryReceiptLine  $sampleItem
   	 * @return \Illuminate\Http\Response
   	 */
   	public function restore($id)
   	{
   	    $item = SalesDeliveryReceiptLine::withTrashed()->findOrFail($id);
   	    $item->unarchive();

   	    return response()->json([
   	        'message' => "You have successfully restored {$item->id}",
   	    ]);
   	}

      /**
       * Approve the specified resource from storage.
       *
       * @param  \App\SalesDeliveryReceiptLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function approve($id)
      {
          $item = SalesDeliveryReceiptLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Approved';
          $item->update([
            'approved_on' => now(),
            'rejected_on' => null
          ]);

          return response()->json([
              'message' => "You have successfully approved {$item->sales_delivery_receipt_line_number}",
          ]);
      }

      /**
       * Reject the specified resource from storage.
       *
       * @param  \App\SalesDeliveryReceiptLine  $sampleItem
       * @return \Illuminate\Http\Response
       */
      public function reject($id)
      {
          $item = SalesDeliveryReceiptLine::withTrashed()->findOrFail($id);
          $item->line_status = 'Rejected';
          $item->update([
            'rejected_on' => now(),
            'approved_on' => null,
          ]);

          return response()->json([
              'message' => "You have successfully rejected {$item->sales_delivery_receipt_line_number}",
          ]);
      }
}
