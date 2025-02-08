@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Vendor Payment Journals </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Vendor Payment Journals</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-payment-journal-table 
        fetch-url="{{ route('vendor-payment-journals.fetch') }}"
        submit-url="{{ route('vendor-payment-journals.store') }}"
        :cost_centers="{{ $cost_centers }}"
        :departments="{{ $departments }}"
        :expense_purposes="{{ $expense_purposes }}"
        :clients="{{ $clients }}"
        create-url="{{ route('vendor-payment-journals.create') }}"
        status-update-url="{{ route('vendor-payment-journals.header-status-update') }}"
        ></vendor-payment-journal-table>
    </section>
</div>

@endsection