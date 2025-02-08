@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Vendor <small>({{ $item->renderName() }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#vendor-information" href="javascript:void(0)" data-toggle="tab">Vendor Information</a></li>
                        <li class="nav-item"><a class="nav-link" href="#purchase-orders" href="javascript:void(0)" data-toggle="tab">Purchase Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="#vendor-invoices" href="javascript:void(0)" data-toggle="tab">Vendor Invoices</a></li>
                        <li class="nav-item"><a class="nav-link" href="#vendor-payments" href="javascript:void(0)" data-toggle="tab">Vendor Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="#subsiduary-ledger" href="javascript:void(0)" data-toggle="tab">Subsidiary Ledger</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bank-account" href="javascript:void(0)" data-toggle="tab">Bank Account</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="vendor-information">
                            <vendor-view
                                submit-url="{{ route('vendors.update', $item->id) }}"
                                fetch-url="{{ route('vendors.fetch-item', $item->id) }}"
                            ></vendor-view>
                        </div>
                        <div class="tab-pane show" id="purchase-orders">
                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval" href="javascript:void(0)" data-toggle="tab">For confirmation</a></li>
                                            <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#confirmed" href="javascript:void(0)" data-toggle="tab">Confirmed</a></li>
                                            <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#invoiced" href="javascript:void(0)" data-toggle="tab">Invoiced</a></li>
                                        </ul>
                                    </div>

                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="for_approval">
                                                <purchase-order-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-1"
                                                    fetch-url="{{ route('purchase-orders.fetch', ['for_confirmation' => 1, 'vendor_account' => $item->vendor_account]) }}"
                                                ></purchase-order-table>
                                            </div>
                                            <div class="tab-pane" id="confirmed">
                                                <purchase-order-table
                                                    :clients="{{ $clients }}"
                                                    ref="table-2"
                                                    disabled
                                                    fetch-url="{{ route('purchase-orders.fetch', ['confirmed' => 1, 'vendor_account' => $item->vendor_account]) }}"
                                                ></purchase-order-table>
                                            </div>
                                            <div class="tab-pane" id="invoiced">
                                                <purchase-order-table
                                                    :clients="{{ $clients }}"
                                                    ref="table-3"
                                                    disabled
                                                    fetch-url="{{ route('purchase-orders.fetch', ['invoiced' => 1, 'vendor_account' => $item->vendor_account]) }}"
                                                ></purchase-order-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="vendor-invoices">
                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a @click="initList('table-4')" class="nav-link active" href="#for_approval_vl" href="javascript:void(0)" data-toggle="tab">For Approval</a></li>
                                            <li class="nav-item"><a @click="initList('table-5')" class="nav-link" href="#approved_vl" href="javascript:void(0)" data-toggle="tab">Approved</a></li>
                                            <li class="nav-item"><a @click="initList('table-6')" class="nav-link" href="#posted_vl" href="javascript:void(0)" data-toggle="tab">Posted</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="for_approval_vl">
                                                <vendor-invoice-table 
                                                :clients="{{ $clients }}"
                                                ref="table-4"
                                                fetch-url="{{ route('vendor-invoices.fetch',  ['for_approval' => 1, 'vendor_account' => $item->vendor_account ]) }}"
                                                ></vendor-invoice-table>
                                            </div>
                                            <div class="tab-pane" id="approved_vl">
                                                <vendor-invoice-table 
                                                :clients="{{ $clients }}"
                                                ref="table-5"
                                                fetch-url="{{ route('vendor-invoices.fetch',  ['approved' => 1, 'vendor_account' => $item->vendor_account ] ) }}"
                                                ></vendor-invoice-table>
                                            </div>
                                            <div class="tab-pane" id="posted_vl">
                                                <vendor-invoice-table 
                                                :clients="{{ $clients }}"
                                                ref="table-6"
                                                fetch-url="{{ route('vendor-invoices.fetch',  ['posted' => 1, 'vendor_account' => $item->vendor_account ] ) }}"
                                                ></vendor-invoice-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="vendor-payments">
                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a @click="initList('table-7')" class="nav-link active" href="#for_approval_vp" href="javascript:void(0)" data-toggle="tab">For Approval</a></li>
                                            <li class="nav-item"><a @click="initList('table-8')" class="nav-link" href="#approved_vp" href="javascript:void(0)" data-toggle="tab">Approved</a></li>
                                            <li class="nav-item"><a @click="initList('table-9')" class="nav-link" href="#posted_vp" href="javascript:void(0)" data-toggle="tab">Posted</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="for_approval_vp">
                                                <vendor-payment-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-7"
                                                    fetch-url="{{ route('vendor-payments.fetch',  ['pending' => 0, 'vendor_account' => $item->vendor_account ]) }}"
                                                ></vendor-payment-table>
                                            </div>
                                            <div class="tab-pane" id="approved_vp">
                                                <vendor-payment-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-8"
                                                    fetch-url="{{ route('vendor-payments.fetch',  ['approved_payment' => 1, 'vendor_account' => $item->vendor_account ] ) }}"
                                                    :is-approved="true"
                                                ></vendor-payment-table>
                                            </div>
                                            <div class="tab-pane" id="posted_vp">
                                                <vendor-payment-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-9"
                                                    fetch-url="{{ route('vendor-payments.fetch',  ['posted_payment' => 1, 'vendor_account' => $item->vendor_account ] ) }}"
                                                    :is-posted="true"
                                                ></vendor-payment-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="subsiduary-ledger">
                            <subsidiary-view
                                :clients="{{ $clients }}"
                                invoice-approval-url="{{ route('po-invoice-approval-journals.fetch-vouchers', ['vendor_account' => $item->vendor_account]) }}"
                                vendor-payment-url="{{ route('vendor-payment-journals.fetch-vouchers', ['vendor_account' => $item->vendor_account]) }}"
                                general-journal-url="{{ route('general-journal.fetch-vouchers', ['vendor_account' => $item->vendor_account]) }}"
                            ></subsidiary-view>
                        </div>
                        <div class="tab-pane" id="bank-account">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#vendor-bank-account-table-active" href="javascript:void(0)" data-toggle="tab">Active</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#vendor-bank-account-table-archived" href="javascript:void(0)" data-toggle="tab">Archived</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#vendor-bank-account-table-expired" href="javascript:void(0)" data-toggle="tab">Expired</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="vendor-bank-account-table-active">
                                            <vendor-bank-account-table
                                                :clients="{{ $clients }}"
                                                create-url="{{ route('vendor-bank-accounts.create', $item->id) }}"
                                                fetch-url="{{ route('vendor-bank-accounts.fetch', ['vendor_account' => $item->vendor_account]) }}"
                                            ></vendor-bank-account-table>
                                        </div>
                                        <div class="tab-pane" id="vendor-bank-account-table-archived">
                                            <vendor-bank-account-table
                                                :clients="{{ $clients }}"
                                                create-url="{{ route('vendor-bank-accounts.create', $item->id) }}"
                                                fetch-url="{{ route('vendor-bank-accounts.fetch', ['vendor_account' => $item->vendor_account, 'archived' => 1]) }}"
                                            ></vendor-bank-account-table>
                                        </div>
                                        <div class="tab-pane" id="vendor-bank-account-table-expired">
                                            <vendor-bank-account-table
                                                :clients="{{ $clients }}"
                                                create-url="{{ route('vendor-bank-accounts.create', $item->id) }}"
                                                fetch-url="{{ route('vendor-bank-accounts.fetch', ['vendor_account' => $item->vendor_account, 'expired' => 1]) }}"
                                            ></vendor-bank-account-table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection