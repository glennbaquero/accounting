@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Inventory Journals </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Inventory Journals</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <inventory-journal-table
            type="overview" 
            fetch-url="{{ route('inventory-journals.fetch') }}"
            submit-url="{{ route('inventory-journals.store') }}"
            :cost_centers="{{ $cost_centers }}"
            :departments="{{ $departments }}"
            :expense_purposes="{{ $expense_purposes }}"
            :clients="{{ $clients }}"
            create-url="{{ route('inventory-journals.create') }}"
            status-update-url="{{ route('inventory-journals.header-status-update') }}"
        ></inventory-journal-table>
    </section>
</div>

@endsection