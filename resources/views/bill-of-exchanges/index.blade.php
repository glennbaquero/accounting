@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Bill of Exchange </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Bill of Exchange</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">


        <div class="col-xs-12">
            {{-- <div class="mb-4">
                <a href="{{ route('customer-payment-journals.create') }}" class="btn btn-primary text-white">
                    <i class="fa fa-plus"></i>
                    Create
                </a>
            </div> --}}
            
            <div class="card">
                <div class="card-body">
                    <bill-of-exchange-table 
                        fetch-url="{{ route('bill-of-exchanges.fetch') }}"
                        submit-url="{{ route('bill-of-exchanges.store') }}"
                        :cost_centers="{{ $cost_centers }}"
                        :departments="{{ $departments }}"
                        :expense_purposes="{{ $expense_purposes }}"
                        :clients="{{ $clients }}"
                        status-update-url="{{ route('bill-of-exchanges.header-status-update') }}"
                        create-url="{{ route('bill-of-exchanges.create') }}"
                    ></bill-of-exchange-table>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection