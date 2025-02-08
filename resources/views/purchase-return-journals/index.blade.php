@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Purchase Return Order Journals </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Purchase Return Order Journals</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <purchase-return-journal-table
            type="overview" 
            fetch-url="{{ route('purchase-return-journals.fetch') }}"
            submit-url="{{ route('purchase-return-journals.store') }}"
            :cost_centers="{{ $cost_centers }}"
            :departments="{{ $departments }}"
            :expense_purposes="{{ $expense_purposes }}"
            :clients="{{ $clients }}"
            create-url="{{ route('purchase-return-journals.create') }}"
            status-update-url="{{ route('purchase-return-journals.header-status-update') }}"
        ></purchase-return-journal-table>
    </section>
</div>

@endsection