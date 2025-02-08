@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Vendor Invoices </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Vendor Invoices</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval" href="javascript:void(0)" data-toggle="tab">Vendor Invoice Aging Report</a></li>
                        {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="for_approval">
                            <vendor-invoice-aging-table 
                            :for-aging-report="true"
                            ref="table-1"
                            fetch-url="{{ route('vendor-invoices-aging.fetch',  ['aging' => 1 ]) }}"
                            ></vendor-invoice-aging-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection