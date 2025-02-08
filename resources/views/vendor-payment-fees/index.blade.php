@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Vendor Payment Fees </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Vendor Payment Fees</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('vendor-payment-fees.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#vendor-payment-fees-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#vendor-payment-fees-archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="vendor-payment-fees-active">
                            <vendor-payment-fee-table 
                                fetch-url="{{ route('vendor-payment-fees.fetch') }}"
                                ref="table-1"
                            ></vendor-payment-fee-table>
                        </div>
                        <div class="tab-pane" id="vendor-payment-fees-archived">
                            <vendor-payment-fee-table 
                                fetch-url="{{ route('vendor-payment-fees.fetch', ['archived' => 1]) }}"
                                ref="table-2"
                                disabled
                            ></vendor-payment-fee-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection