@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Customer Invoice Journals </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Customer Invoice Journals</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-invoice-approval-journal-table 
        fetch-url="{{ route('so-invoice-approval-journals.fetch') }}"
        submit-url="{{ route('so-invoice-approval-journals.store') }}"
        :cost_centers="{{ $cost_centers }}"
        :departments="{{ $departments }}"
        :expense_purposes="{{ $expense_purposes }}"
        :clients="{{ $clients }}"
        create-url="{{ route('so-invoice-approval-journals.create') }}"
        status-update-url="{{ route('so-invoice-approval-journals.header-status-update') }}"
        ></customer-invoice-approval-journal-table>
    </section>
</div>

@endsection