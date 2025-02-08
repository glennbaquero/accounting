@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Customer Invoice Approval Journal</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('so-invoice-approval-journals.index') }}">Customer Invoice Approval Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <journal-create-update
            submit-url="{{ route('so-invoice-approval-journals.store') }}"
            invoice-journal-number="customer_invoice_journal_number"
            invoice-header-title="Journal Header Number"
            fetch-url="{{ route('so-invoice-approval-journals.fetch-item') }}"
        ></journal-create-update>
    </section>
</div>

@endsection