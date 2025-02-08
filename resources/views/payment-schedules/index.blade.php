@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Payment Schedules</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Payment Schedules</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('payment-schedules.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#payment-schedules-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#payment-schedules-archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="payment-schedules-active">
                            <payment-schedule-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('payment-schedules.fetch') }}"
                            ></payment-schedule-table>
                        </div>
                        <div class="tab-pane" id="payment-schedules-archived">
                            <payment-schedule-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('payment-schedules.fetch', ['archived' => 1]) }}"
                            ></payment-schedule-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection