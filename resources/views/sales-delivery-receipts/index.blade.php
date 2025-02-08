@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Sales Delivery Receipts </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Sales Delivery Receipts</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('sales-delivery-receipts.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval" href="javascript:void(0)" data-toggle="tab">For approval</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#approved" href="javascript:void(0)" data-toggle="tab">Approved</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#posted" href="javascript:void(0)" data-toggle="tab">Posted</a></li>
                        {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="for_approval">
                            <sales-delivery-receipt-table 
                                :clients="{{ $clients }}"
                                ref="table-1"
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('sales-delivery-receipts.fetch', ['for_approval' => 1]) }}"
                            ></sales-delivery-receipt-table>
                        </div>
                        <div class="tab-pane" id="approved">
                            <sales-delivery-receipt-table
                                :clients="{{ $clients }}"
                                ref="table-2"
                                :clients="{{ $clients }}"
                                disabled
                                fetch-url="{{ route('sales-delivery-receipts.fetch', ['approved' => 1]) }}"
                            ></sales-delivery-receipt-table>
                        </div>
                        <div class="tab-pane" id="posted">
                            <sales-delivery-receipt-table
                                :clients="{{ $clients }}"
                                ref="table-3"
                                :clients="{{ $clients }}"
                                disabled
                                fetch-url="{{ route('sales-delivery-receipts.fetch', ['posted' => 1]) }}"
                            ></sales-delivery-receipt-table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection