<?php

namespace App\Http\Controllers\Budgets;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Budgets\BudgetStoreRequest;
use App\Models\Budgets\Budget;
use App\Models\BudgetLines\BudgetLine;
use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\GeneralLedgers\GeneralLedgerLine;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('budgets.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $count = Budget::withTrashed()->count() + 1;
        $budget_id = 'BDG-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        return view('budgets.create', [
            'budget_id' => $budget_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Budgets\BudgetStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetStoreRequest $request)
    {
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = Budget::store($request);

        $this->syncLines($item, $request->input('budget_lines'));

        $message = "You have successfully created {$item->budget_name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Budget::withTrashed()->findOrFail($id);

        return view('budgets.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Budgets\BudgetStoreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetStoreRequest $request, $id)
    {
        $item = Budget::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->budget_name}";
        $request['updated_by'] = auth()->user()->id;

        $item = Budget::store($request, $item);

        $this->syncLines($item, $request->input('budget_lines'));

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Budget::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->budget_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Budget::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->budget_name}",
        ]);
    }

    /**
     * Create/update/delete budget lines submitted as a single JSON payload
     * (`budget_lines`) alongside the header, mirroring how order lines are
     * submitted together elsewhere in the app.
     *
     * @param  \App\Models\Budgets\Budget  $item
     * @param  string|null  $linesPayload
     * @return void
     */
    protected function syncLines($item, $linesPayload)
    {
        if (!$linesPayload) {
            return;
        }

        $lines = is_array($linesPayload) ? $linesPayload : json_decode($linesPayload, true);

        if (!is_array($lines)) {
            return;
        }

        $submittedIds = [];

        foreach ($lines as $line) {
            $line['budget_id'] = $item->budget_id;
            $line['client_id'] = $item->client_id;
            $line['created_by'] = $line['created_by'] ?? auth()->user()->id;
            $line['updated_by'] = auth()->user()->id;

            $existing = !empty($line['id']) ? BudgetLine::where('budget_id', $item->budget_id)->where('id', $line['id'])->first() : null;

            $budgetLine = BudgetLine::store($line, $existing);

            $submittedIds[] = $budgetLine->id;
        }

        /* Archive any existing lines that were removed from the submitted set */
        BudgetLine::where('budget_id', $item->budget_id)->whereNotIn('id', $submittedIds)->get()->each(function ($line) {
            $line->archive();
        });
    }

    /**
     * Budget vs. Actual variance per line, comparing the planned amount
     * against net GL movement on the same main account within the
     * corresponding fiscal period.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function variance($id)
    {
        $item = Budget::withTrashed()->findOrFail($id);

        $result = $item->budget_lines()->get()->map(function ($line) use ($item) {
            $actual = 0;
            $period = $line->fiscal_period_id ? FiscalPeriod::where('fiscal_period_id', $line->fiscal_period_id)->first() : null;

            if ($line->main_account && $period) {
                $actual = GeneralLedgerLine::where('main_account', $line->main_account)
                    ->where('client_id', $item->client_id)
                    ->whereDate('ledger_transaction_date', '>=', $period->fiscal_period_start_date)
                    ->whereDate('ledger_transaction_date', '<=', $period->fiscal_period_end_date)
                    ->selectRaw('COALESCE(SUM(debit_amount), 0) - COALESCE(SUM(credit_amount), 0) as net')
                    ->value('net') ?? 0;
            }

            return [
                'id' => $line->id,
                'main_account_code' => $line->main_account_code,
                'main_account_name' => $line->main_account_name,
                'fiscal_period_code' => $line->fiscal_period_code,
                'department' => $line->department,
                'budgeted_amount' => (float) $line->budgeted_amount,
                'actual_amount' => (float) $actual,
                'variance' => (float) $line->budgeted_amount - (float) $actual,
            ];
        });

        return response()->json([
            'items' => $result,
            'total_budgeted' => $result->sum('budgeted_amount'),
            'total_actual' => $result->sum('actual_amount'),
            'total_variance' => $result->sum('variance'),
        ]);
    }
}
