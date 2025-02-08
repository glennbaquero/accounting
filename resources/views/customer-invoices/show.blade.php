@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Customer Invoice <small>({{ $item->customer_invoice_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-invoices.index') }}">Customer Invoice</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#info" href="javascript:void(0)" data-toggle="tab">Information</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sdr" href="javascript:void(0)" data-toggle="tab">Sales Delivery Receipt</a></li>
                        <li class="nav-item"><a class="nav-link" href="#cp" href="javascript:void(0)" data-toggle="tab">Customer Payment</a></li>
                        {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="info">
                            <customer-invoice-view
                                submit-url="{{ route('customer-invoices.update', $item->id) }}"
                                fetch-url="{{ route('customer-invoices.fetch-item', [$item->sales_order_number ?? 'null', $item->id]) }}"
                                generate-invoice-payment-url="{{ route('customer-payments.create', $item->id) }}"
                                :show-confirm-button="true"
                                customer-payments-approval="{{ route('customer-payments.fetch',  ['pending' => 0, 'invoice_id' => $item->id ]) }}"
                                customer-payments-approved="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'invoice_id' => $item->id ] ) }}"
                                customer-payments-posted="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'invoice_id' => $item->id ] ) }}"
                                invoice-approval-url="{{ route('so-invoice-approval-journals.fetch-vouchers', ['customer_invoice_number' => $item->customer_invoice_number]) }}"
                                customer-payment-url="{{ route('customer-payment-journals.fetch-vouchers', ['customer_invoice_number' => $item->customer_invoice_number]) }}"
                                general-journal-url="{{ route('general-journal.fetch-vouchers', ['customer_invoice_number' => $item->customer_invoice_number]) }}"
                                print-url="{{ route('customer-invoices.print',  ['id' => $item->id ] ) }}"

                                payment-schedule-url="{{ route('payment-schedules.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
                                interest-calculation-url="{{ route('interest-calculations.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
                                interest-note-url="{{ route('interest-notes.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
                                interest-setup-url="{{ route('interest-setups.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
                                interest-adjustment-url="{{ route('interest-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"

                                collection-url="{{ route('collections.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
                                boe-url="{{ route('bills-exchanges.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
                                boe-adjustment-url="{{ route('bills-exchange-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
                            ></customer-invoice-view>
                        </div>
                        <div class="tab-pane" id="sdr">
                            <section class="content">
                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval" href="javascript:void(0)" data-toggle="tab">For approval</a></li>
                                                <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#approved" href="javascript:void(0)" data-toggle="tab">Approved</a></li>
                                                <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#posted" href="javascript:void(0)" data-toggle="tab">Posted</a></li>
                                                {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                                            </ul>
                                        </div>

                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane show active" id="for_approval">
                                                    <sales-delivery-receipt-table 
                                                        ref="table-1"
                                                        :clients="{{ $clients }}"
                                                        fetch-url="{{ route('sales-delivery-receipts.fetch', ['for_approval' => 1, 'customer_invoice_id' => $item->id]) }}"
                                                    ></sales-delivery-receipt-table>
                                                </div>
                                                <div class="tab-pane" id="approved">
                                                    <sales-delivery-receipt-table
                                                        ref="table-2"
                                                        :clients="{{ $clients }}"
                                                        disabled
                                                        fetch-url="{{ route('sales-delivery-receipts.fetch', ['approved' => 1, 'customer_invoice_id' => $item->id]) }}"
                                                    ></sales-delivery-receipt-table>
                                                </div>
                                                <div class="tab-pane" id="posted">
                                                    <sales-delivery-receipt-table
                                                        ref="table-3"
                                                        :clients="{{ $clients }}"
                                                        disabled
                                                        fetch-url="{{ route('sales-delivery-receipts.fetch', ['posted' => 1, 'customer_invoice_id' => $item->id]) }}"
                                                    ></sales-delivery-receipt-table>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="tab-pane" id="cp">
                            <!-- Main content -->
                            <section class="content">

                                <div class="mb-4">
                                    <a href="{{ route('customer-payment-fees.create') }}" class="btn btn-primary text-white">
                                        <i class="fa fa-plus"></i>
                                        Create
                                    </a>
                                </div>

                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#customer-payment-fees-active" data-toggle="tab">Active</a></li>
                                                <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#customer-payment-fees-archived" data-toggle="tab">Archive</a></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane show active" id="customer-payment-fees-active">
                                                    <customer-payment-fee-table 
                                                        fetch-url="{{ route('customer-payment-fees.fetch', ['customer_invoice_id' => $item->id]) }}"
                                                        ref="table-1"
                                                    ></customer-payment-fee-table>
                                                </div>
                                                <div class="tab-pane" id="customer-payment-fees-archived">
                                                    <customer-payment-fee-table 
                                                        fetch-url="{{ route('customer-payment-fees.fetch', ['archived' => 1, 'customer_invoice_id' => $item->id]) }}"
                                                        ref="table-2"
                                                        disabled
                                                    ></customer-payment-fee-table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection