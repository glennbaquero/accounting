@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Invoice Approval Journals </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Invoice Approval Journals</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <invoice-approval-journal-table
            type="overview" 
            fetch-url="{{ route('po-invoice-approval-journals.fetch') }}"
            submit-url="{{ route('po-invoice-approval-journals.store') }}"
            :cost_centers="{{ $cost_centers }}"
            :departments="{{ $departments }}"
            :expense_purposes="{{ $expense_purposes }}"
            :clients="{{ $clients }}"
            create-url="{{ route('po-invoice-approval-journals.create') }}"
            status-update-url="{{ route('po-invoice-approval-journals.header-status-update') }}"
        ></invoice-approval-journal-table>
    </section>
</div>

@endsection