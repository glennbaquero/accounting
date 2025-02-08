<?php

namespace App\Http\Controllers\ClosingTransactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClosingTransactions\ClosingTransactionPasswordRequest;
use App\Http\Requests\ClosingTransactions\ClosingTransactionStoreRequest;
use App\Models\GeneralLedgers\ClosingTransaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ClosingTransactionController extends Controller
{
    public function index()
    {
        return view('closing-transactions.index', [
            //
        ]);
    }

    public function create()
    {
        return view('closing-transactions.create', [
            //
        ]);
    }

    public function store(ClosingTransactionStoreRequest $request)
    {
        $item = ClosingTransaction::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = ClosingTransaction::withTrashed()->findOrFail($id);

        return view('closing-transactions.show', [
            'item' => $item,
        ]);
    }

    public function update(ClosingTransactionStoreRequest $request, $id)
    {
        $item = ClosingTransaction::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->id}";

        $item = ClosingTransaction::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = ClosingTransaction::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->id}",
        ]);
    }

    public function restore($id)
    {
        $item = ClosingTransaction::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->id}",
        ]);
    }

    
    public function markAsApproved($id)
    {
        $item = ClosingTransaction::withTrashed()->findOrFail($id);

        if(!$item->checkIfReviewed()) {
            throw ValidationException::withMessages(['messages' => 'Please mark this closing transaction as review first']);
        }

        if($item) {
            if($item->canApproved()) {
                $item->update([
                    'approved_by' => auth()->user()->id,
                    'approved_on' => now(),
                ]);

                return response()->json([
                    'message' => "You have successfully approved this closing transaction",
                ]);

            }else {
                throw ValidationException::withMessages(['messages' => 'This closing transaction is already been approved']);
            }
        }else {
            throw ValidationException::withMessages(['messages' => 'Closing Transactoin not found : closing transaction approval failed']);
        }
    }

    
    public function markAsReviewed($id)
    {
        $item = ClosingTransaction::withTrashed()->findOrFail($id);

        if($item) {
            if($item->canReviewed()) {

                $item->update([
                    'reviewed_by' => auth()->user()->id,
                    'reviewed_on' => now(),
                ]);

                return response()->json([
                    'message' => "Closing Transaction has been successfully marked as reviewed by you",
                ]);

            }else {
                throw ValidationException::withMessages(['messages' => 'This closing transaction has already been reviewed']);
            }
        }else {
            throw ValidationException::withMessages(['messages' => 'Closing Transactoin not found : closing transaction reviewal failed']);
        }
    }

    public function setPassword(ClosingTransactionPasswordRequest $request)
    {
        $closing_transaction = ClosingTransaction::find($request->id);

        if($closing_transaction) {

            if(!$closing_transaction->hasPassword()) {

                $closing_transaction->update([
                    'password' => Hash::make($request->password),
                    'password_set_by' => auth()->user()->id,
                    'password_set_on' => now(),
                ]);

                return response()->json([
                    'message' => "Closing Transaction password has been successfully set",
                ]);

            }else {
               
                throw ValidationException::withMessages(['messages' => 'Closing Transaction password is already been set by ' .  $closing_transaction->password_set_by_user->renderName() ]);

            }
            
        }else {
            throw ValidationException::withMessages(['messages' => 'Closing Transaction not found : password setting failed']);
        }
 
    }

    public function canSetPassword($id)
    {
        $closing_transaction = ClosingTransaction::find($id);

        if($closing_transaction) {

            if(!$closing_transaction->hasPassword()) {

                return response()->json([
                    'message' => "Closing Transaction has no password",
                ]);

            }else {
               
                throw ValidationException::withMessages(['messages' => 'Closing Transaction password is already been set by ' .  $closing_transaction->password_set_by_user->renderName() ]);

            }
            
        }else {
            throw ValidationException::withMessages(['messages' => 'Closing Transaction not found : password setting failed']);
        }
 
    }
}
