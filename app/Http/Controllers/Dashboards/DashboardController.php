<?php

namespace App\Http\Controllers\Dashboards;


use App\Http\Controllers\Controller;
use App\Models\AdminSetups\Client;
use App\Models\Customers\Customer;
use App\Models\Invoices\VendorInvoice;
use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\Vendors\Vendor;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboards.index', [
            //
        ]);
    }

    public function fetchSmallBoxData($id)
    {
        $pending_invoices = VendorInvoice::getCompanyData()->where('client_id', $id)->where('approved_by', '<>', null)->count();
        $pending_purchase = PurchaseOrder::getCompanyData()->where('client_id', $id)->where('for_confirmation', '<>', null)->count();
        $total_vendors = Vendor::getCompanyData()->where('client_id', $id)->count();
        $total_customers = Customer::getCompanyData()->where('client_id', $id)->count();

        return response()->json([
            'pending_invoices' => $pending_invoices,
            'pending_purchase' => $pending_purchase,
            'total_vendors' => $total_vendors,
            'total_customers' => $total_customers,
        ]);
    }


    public function fetchProfitAndLoss($id)
    {

        $client = Client::find($id);

        $income = 0;
        $expense = 0;
        $amount = 0;
        $income_percent = 0;
        $expense_percent = 0;
        $percent = 0;

        if($client) {

            $ledger = $client->getActiveLedger();

            if($ledger) {
                $general_ledger = $ledger->general_ledger;
                $income = $general_ledger->getIncome();
                $expense = $general_ledger->getExpense();
                $amount = $income - $expense;
                $total = $income + $expense;
                $income_percent = $total ? ($income / $total) * 100 : 0;
                $expense_percent = $total ? ($expense / $total) * 100 : 0;
                $percent = $income_percent - $expense_percent;
    
            }else {
                
                return response()->json([
                    'income' => $income,
                    'expense' => $expense,
                    'amount' => $amount,
                    'income_percent' => $income_percent,
                    'expense_percent' => $expense_percent,
                    'percent' => $percent,
                ]);
            }

        }else {
            return response()->json([
                'income' => $income,
                'expense' => $expense,
                'amount' => $amount,
                'income_percent' => $income_percent,
                'expense_percent' => $expense_percent,
                'percent' => $percent,
            ]);
        }

        return response()->json([
            'income' => $income,
            'expense' => $expense,
            'amount' => $amount,
            'income_percent' => $income_percent,
            'expense_percent' => $expense_percent,
            'percent' => $percent,
        ]);
    }
}
