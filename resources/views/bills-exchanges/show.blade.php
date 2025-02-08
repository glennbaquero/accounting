@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bills Of Exchange <small>({{ $item->bills_of_exchange }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bills-exchanges.index') }}">Bills Of Exchange</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Bills Of Exchange</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bills-exchange-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('bills-exchanges.fetch-item', $item->id) }}"
            submit-url="{{ route('bills-exchanges.update', $item->id) }}"

            payment-schedule-url="{{ route('payment-schedules.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-calculation-url="{{ route('interest-calculations.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-note-url="{{ route('interest-notes.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-setup-url="{{ route('interest-setups.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-adjustment-url="{{ route('interest-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"

            collection-url="{{ route('collections.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            boe-adjustment-url="{{ route('bills-exchange-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
        ></bills-exchange-view>
    </section>
</div>

@endsection