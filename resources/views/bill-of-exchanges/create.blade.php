@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Bill of Exchange</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bill-of-exchanges.index') }}">Bill of Exchange</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bill-of-exchange-create-update
            submit-url="{{ route('bill-of-exchanges.store') }}"
            invoice-journal-number="bill_exchange_journal_number"
            invoice-header-title="Journal Header Number"
            fetch-url="{{ route('bill-of-exchanges.fetch-item') }}"
        ></bill-of-exchange-create-update>
    </section>
</div>

@endsection