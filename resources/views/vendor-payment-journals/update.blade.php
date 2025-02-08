@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Vendor Payment Journal <small>({{ $item->customer_invoice_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-payment-journals.index') }}">Vendor Payment Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <journal-create-update
            submit-url="{{ route('vendor-payment-journals.update', $item->id) }}"
            invoice-journal-number="vendor_payment_journal_number"
            invoice-header-title="Journal Header Number"
            fetch-url="{{ route('vendor-payment-journals.fetch-item', $item->id) }}"
        ></journal-create-update>
    </section>
</div>

@endsection