@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Customer <small>({{ $item->customer_account }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#customer-information" href="javascript:void(0)" data-toggle="tab">Customer Information</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bank-account" href="javascript:void(0)" data-toggle="tab">Bank Account</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sales-orders" href="javascript:void(0)" data-toggle="tab">Sales Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="#customer-invoices" href="javascript:void(0)" data-toggle="tab">Customer Invoices</a></li>
                        <li class="nav-item"><a class="nav-link" href="#customer-payments" href="javascript:void(0)" data-toggle="tab">Customer Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="#subsiduary-ledger" href="javascript:void(0)" data-toggle="tab">Subsidiary Ledger</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane show active" id="customer-information">
                            <customer-view
                                submit-url="{{ route('customers.update', $item->id) }}"
                                fetch-url="{{ route('customers.fetch-item', $item->id) }}"
                            ></customer-view>
                        </div>
                        <div class="tab-pane show" id="bank-account">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#customer-bank-account-table-active" href="javascript:void(0)" data-toggle="tab">Active</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#customer-bank-account-table-archived" href="javascript:void(0)" data-toggle="tab">Archived</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#customer-bank-account-table-expired" href="javascript:void(0)" data-toggle="tab">Expired</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="customer-bank-account-table-active">
                                            <customer-bank-account-table
                                                :clients="{{ $clients }}"
                                                create-url="{{ route('customer-bank-accounts.create', $item->id) }}"
                                                fetch-url="{{ route('customer-bank-accounts.fetch', ['customer_account' => $item->customer_account]) }}"
                                            ></customer-bank-account-table>
                                        </div>
                                        <div class="tab-pane" id="customer-bank-account-table-archived">
                                            <customer-bank-account-table
                                                :clients="{{ $clients }}"
                                                create-url="{{ route('customer-bank-accounts.create', $item->id) }}"
                                                fetch-url="{{ route('customer-bank-accounts.fetch', ['customer_account' => $item->customer_account, 'archived' => 1]) }}"
                                            ></customer-bank-account-table>
                                        </div>
                                        <div class="tab-pane" id="customer-bank-account-table-expired">
                                            <customer-bank-account-table
                                                :clients="{{ $clients }}"
                                                create-url="{{ route('customer-bank-accounts.create', $item->id) }}"
                                                fetch-url="{{ route('customer-bank-accounts.fetch', ['customer_account' => $item->customer_account, 'expired' => 1]) }}"
                                            ></customer-bank-account-table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="sales-orders">
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
                                                <sales-order-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-1"
                                                    fetch-url="{{ route('sales-orders.fetch', ['for_confirmation' => 1, 'customer_account' => $item->customer_account]) }}"
                                                ></sales-order-table>
                                            </div>
                                            <div class="tab-pane" id="confirmed">
                                                <sales-order-table
                                                    :clients="{{ $clients }}"
                                                    ref="table-2"
                                                    disabled
                                                    fetch-url="{{ route('sales-orders.fetch', ['confirmed' => 1, 'customer_account' => $item->customer_account]) }}"
                                                ></sales-order-table>
                                            </div>
                                            <div class="tab-pane" id="invoiced">
                                                <sales-order-table
                                                    :clients="{{ $clients }}"
                                                    ref="table-3"
                                                    disabled
                                                    fetch-url="{{ route('sales-orders.fetch', ['invoiced' => 1, 'customer_account' => $item->customer_account]) }}"
                                                ></sales-order-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="customer-invoices">
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
                                                <customer-invoice-table 
                                                :clients="{{ $clients }}"
                                                ref="table-4"
                                                fetch-url="{{ route('customer-invoices.fetch',  ['for_approval' => 1, 'customer_account' => $item->customer_account ]) }}"
                                                ></customer-invoice-table>
                                            </div>
                                            <div class="tab-pane" id="approved_vl">
                                                <customer-invoice-table 
                                                :clients="{{ $clients }}"
                                                ref="table-5"
                                                fetch-url="{{ route('customer-invoices.fetch',  ['approved' => 1, 'customer_account' => $item->customer_account ] ) }}"
                                                ></customer-invoice-table>
                                            </div>
                                            <div class="tab-pane" id="posted_vl">
                                                <customer-invoice-table 
                                                :clients="{{ $clients }}"
                                                ref="table-6"
                                                fetch-url="{{ route('customer-invoices.fetch',  ['posted' => 1, 'customer_account' => $item->customer_account ] ) }}"
                                                ></customer-invoice-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="customer-payments">
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
                                                <customer-payment-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-7"
                                                    fetch-url="{{ route('customer-payments.fetch',  ['pending' => 0, 'customer_account' => $item->customer_account ]) }}"
                                                ></customer-payment-table>
                                            </div>
                                            <div class="tab-pane" id="approved_vp">
                                                <customer-payment-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-8"
                                                    fetch-url="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'customer_account' => $item->customer_account ] ) }}"
                                                    :is-approved="true"
                                                ></customer-payment-table>
                                            </div>
                                            <div class="tab-pane" id="posted_vp">
                                                <customer-payment-table 
                                                    :clients="{{ $clients }}"
                                                    ref="table-9"
                                                    fetch-url="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'customer_account' => $item->customer_account ] ) }}"
                                                    :is-posted="true"
                                                ></customer-payment-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="subsiduary-ledger">
                            <subsidiary-view
                                :clients="{{ $clients }}"
                                invoice-approval-url="{{ route('so-invoice-approval-journals.fetch-vouchers', ['customer_account' => $item->customer_account]) }}"
                                customer-payment-url="{{ route('customer-payment-journals.fetch-vouchers', ['customer_account' => $item->customer_account]) }}"
                                general-journal-url="{{ route('general-journal.fetch-vouchers', ['customer_account' => $item->customer_account]) }}"
                            ></subsidiary-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection