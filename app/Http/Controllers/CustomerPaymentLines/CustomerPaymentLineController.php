<?php

namespace App\Http\Controllers\CustomerPaymentLines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SalesOrders\CustomerPaymentLine;
use App\Models\Users\User;

class CustomerPaymentLineController extends Controller
{
    /**
   	 * Remove the specified resource from storage.
   	 *
   	 * @param Integer $id
   	 * @return \Illuminate\Http\Response
   	 */
    public function archive($id)
    {
        $item = CustomerPaymentLine::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully remove the item line {$item->payment_line_number}",
        ]);
    }

   	/**
   	 * Restore the specified resource from storage.
   	 *
   	 * @param Integer $id
   	 * @return \Illuminate\Http\Response
   	 */
    public function restore($id)
    {
        $item = CustomerPaymentLine::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->payment_line_number}",
        ]);
    }

    /**
     * Approve the specified resource from storage.
     *
     * @param Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $item = CustomerPaymentLine::withTrashed()->findOrFail($id);
        $admin_id = auth()->user()->id;

        $item->update([
            'posted_payment' => true,
            'posting_by_id' => auth()->user()->id,
            'posting_date' => now(),
            'posting_by_name' => User::find($admin_id)->renderName(),
        ]);

        return response()->json([
            'message' => "You have successfully approved {$item->payment_line_number}",
        ]);
    }

      /**
       * Reject the specified resource from storage.
       *
       * @param Integer $id
       * @return \Illuminate\Http\Response
       */
        public function reject($id)
        {
            $item = CustomerPaymentLine::withTrashed()->findOrFail($id);
            $admin_id = auth()->user()->id;

            $item->update([
                'is_rejected' => true,
                'rejected_by_id' => $admin_id,
                'rejected_date' => now(),
                'rejected_by_name' => User::find($admin_id)->renderName(),
            ]);

            return response()->json([
                'message' => "You have successfully rejected {$item->payment_line_number}",
            ]);
        }
}
