@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Customer Payment Fees </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Customer Payment Fees</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('customer-payment-fees.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#customer-payment-fees-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#customer-payment-fees-archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="customer-payment-fees-active">
                            <customer-payment-fee-table 
                                fetch-url="{{ route('customer-payment-fees.fetch') }}"
                                ref="table-1"
                            ></customer-payment-fee-table>
                        </div>
                        <div class="tab-pane" id="customer-payment-fees-archived">
                            <customer-payment-fee-table 
                                fetch-url="{{ route('customer-payment-fees.fetch-archive') }}"
                                ref="table-2"
                                disabled
                            ></customer-payment-fee-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection