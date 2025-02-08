@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Customer Payments </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Customer Payments</a></li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="mb-4">
            <a href="{{ route('customer-payments.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval" href="javascript:void(0)" data-toggle="tab">For Approval</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#approved" href="javascript:void(0)" data-toggle="tab">Approved</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#posted" href="javascript:void(0)" data-toggle="tab">Posted</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="for_approval">
                            <customer-payment-table 
                                :clients="{{ $clients }}"
                                ref="table-1"
                                fetch-url="{{ route('customer-payments.fetch',  ['pending' => 0 ]) }}"
                            ></customer-payment-table>
                        </div>
                        <div class="tab-pane" id="approved">
                            <customer-payment-table 
                                :clients="{{ $clients }}"
                                ref="table-2"
                                fetch-url="{{ route('customer-payments.fetch',  ['approved_payment' => 1 ] ) }}"
                                :is-approved="true"
                            ></customer-payment-table>
                        </div>
                        <div class="tab-pane" id="posted">
                            <customer-payment-table 
                                :clients="{{ $clients }}"
                                ref="table-3"
                                fetch-url="{{ route('customer-payments.fetch',  ['posted_payment' => 1 ] ) }}"
                                :is-posted="true"
                            ></customer-payment-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection