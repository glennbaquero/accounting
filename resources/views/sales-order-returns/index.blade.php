@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Sales Order Returns </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Sales Order Returns</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('sales-order-returns.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval" href="javascript:void(0)" data-toggle="tab">For confirmation</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#confirmed" href="javascript:void(0)" data-toggle="tab">Confirmed</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#invoiced" href="javascript:void(0)" data-toggle="tab">Invoiced</a></li>
                        {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="for_approval">
                            <sales-order-returns-table 
                                :clients="{{ $clients }}"
                                ref="table-1"
                                fetch-url="{{ route('sales-order-returns.fetch', ['for_confirmation' => 1]) }}"
                            ></sales-order-returns-table>
                        </div>
                        <div class="tab-pane" id="confirmed">
                            <sales-order-returns-table
                                :clients="{{ $clients }}"
                                ref="table-2"
                                disabled
                                fetch-url="{{ route('sales-order-returns.fetch', ['confirmed' => 1]) }}"
                            ></sales-order-returns-table>
                        </div>
                        <div class="tab-pane" id="invoiced">
                            <sales-order-returns-table
                                :clients="{{ $clients }}"
                                ref="table-3"
                                disabled
                                fetch-url="{{ route('sales-order-returns.fetch', ['invoiced' => 1]) }}"
                            ></sales-order-returns-table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection