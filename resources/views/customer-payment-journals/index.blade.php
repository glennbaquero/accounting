@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Customer Payment Journals </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Customer Payment Journals</a></li>
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
                    <customer-payment-journal-table 
                        fetch-url="{{ route('customer-payment-journals.fetch') }}"
                        submit-url="{{ route('customer-payment-journals.store') }}"
                        :cost_centers="{{ $cost_centers }}"
                        :departments="{{ $departments }}"
                        :expense_purposes="{{ $expense_purposes }}"
                        :clients="{{ $clients }}"
                        create-url="{{ route('customer-payment-journals.create') }}"
                        status-update-url="{{ route('customer-payment-journals.header-status-update') }}"
                    ></customer-payment-journal-table>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection