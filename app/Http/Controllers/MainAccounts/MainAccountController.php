<?php

namespace App\Http\Controllers\MainAccounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\MainAccounts\MainAccountStoreRequest;

use App\Models\MainAccounts\MainAccount;
use App\Models\LedgerSetup\ChartOfAccount;
use App\Models\LinkedMainAccounts\LinkedMainAccount;
use Illuminate\Validation\ValidationException;
use Throwable;

class MainAccountController extends Controller
{

    public function index()
    {
        return view('main-accounts.index', [
            //
        ]);
    }

    public function create()
    {


        return view('main-accounts.create', [

        ]);
    }

    public function create_coa($coa_id)
    {
        if(!$coa_id) {
            return back();
        }

        $coa = ChartOfAccount::withTrashed()->where('coa_id', $coa_id)->first();        

        return view('main-accounts.create_coa', [
            'coa_id' => $coa,
        ]);
    }

    public function store(MainAccountStoreRequest $request)
    {

        if($request->debit_credit_decrease_rule == $request->debit_credit_increase_rule) {
            throw ValidationException::withMessages(['rule error' => 'Increase and Decrease should have different rules']);
        }

        $count = MainAccount::withTrashed()->count();
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = MainAccount::store($request);

        if($request->filled('is_shared')) {
            DB::beginTransaction();
                $company = auth()->user()->company_id;
                $clients = Client::where('company_id',$company)->get();
                $chart_of_account = ChartOfAccount::withTrashed()->findOrFail($item->chart_of_account_id);

                $chart_of_accounts_name = $chart_of_account->coa_name;
                $chart_of_accounts_code = $chart_of_account->coa_code;
                $main_account_code = $item->main_account_id;
                $main_account_type = $item->main_account_type;
                $main_account_category = $item->main_account_category_id;
                $main_account = $item->main_account_id;
                $created_by = auth()->user()->id;
                $company_id = $company;
                $description = $item->description;

                foreach ($clients as $key => $client) {
                    $client_id = $client->id;
                    $count = LinkedMainAccount::withTrashed()->count();
                    $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                    $linked_main_account_code = 'LNKDMNACCT-'.now()->format('m-d-y').'-'.$number;

                    LinkedMainAccount::create([
                        'chart_of_accounts_name' => $chart_of_accounts_name,
                        'chart_of_accounts_code' => $chart_of_accounts_code,
                        'main_account_code' => $main_account_code,
                        'main_account_type' => $main_account_type,
                        'main_account_category' => $main_account_category,
                        'main_account' => $main_account,
                        'linked_main_account_code' => $linked_main_account_code,
                        'created_by' => $created_by,
                        'company_id' => $company_id,
                        'client_id' => $client_id,
                        'description' => $description,
                    ]);
                }
            DB::commit();
        }

        $message = "You have successfully created {$item->main_account_code}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = MainAccount::withTrashed()->findOrFail($id);

        return view('main-accounts.show', [
            'item' => $item,
        ]);
    }

    public function showCoa($id)
    {
        $item = MainAccount::withTrashed()->findOrFail($id);

        return view('main-accounts.show-coa', [
            'item' => $item,
        ]);
    }

    public function update(MainAccountStoreRequest $request, $id)
    {

        if($request->debit_credit_decrease_rule == $request->debit_credit_increase_rule) {
            throw ValidationException::withMessages(['rule error' => 'Increase and Decrease should have different rules']);
        }

        $item = MainAccount::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->main_account_code}";

        $item = MainAccount::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = MainAccount::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->main_account_code}",
        ]);
    }

    public function restore($id)
    {
        $item = MainAccount::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->main_account_code}",
        ]);
    }

    public function attachToLinkedMainAccount(Request $request, $id) {
        try {
            LinkedMainAccount::find($request->linked)->main_accounts()->attach($id);
        } catch (Throwable $error) {
            throw ValidationException::withMessages([$error]);
        }
        return response()->json([
            'message' => "You have successfully added new client",
        ]);
    }

    public function detachToLinkedMainAccount(Request $request, $id) {
        try {
            LinkedMainAccount::find($request->linked)->main_accounts()->detach($id);
        } catch (Throwable $error) {
            throw ValidationException::withMessages([$error]);
        }
        return response()->json([
            'message' => "You have successfully remove the client",
        ]);
    }
}
