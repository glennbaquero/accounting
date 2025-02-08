@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Payment Schedule <small>({{ $item->payment_schedule_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('payment-schedules.index') }}">Payment Schedules</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Payment Schedule</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#payment-schedule-header" data-toggle="tab">Payment Schedule Header</a></li>
                        <li class="nav-item"><a class="nav-link" href="#payment-schedule-lines" data-toggle="tab">Payment Schedule Lines</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="payment-schedule-header">
                            <payment-schedule-view
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('payment-schedules.fetch-item', $item->id) }}"
                                submit-url="{{ route('payment-schedules.update', $item->id) }}"
                            ></payment-schedule-view>
                        </div>
                        <div class="tab-pane" id="payment-schedule-lines">
                            <div class="mb-4">
                                <payment-schedule-line-create
                                    :clients="{{ $clients }}"
                                    :parent="{{ $item }}"
                                    fetch-url="{{ route('payment-schedule-lines.fetch-item') }}"
                                    submit-url="{{ route('payment-schedule-lines.store') }}"
                                ></payment-schedule-line-create>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#payment-schedule-line-active" data-toggle="tab">Active</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#payment-schedule-line-archived" data-toggle="tab">Archive</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="payment-schedule-line-active">
                                                <payment-schedule-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('payment-schedule-lines.fetch', ['payment_schedule_id' => $item->payment_schedule_id]) }}"
                                                ></payment-schedule-line-table>
                                            </div>
                                            <div class="tab-pane" id="payment-schedule-line-archived">
                                                <payment-schedule-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('payment-schedule-lines.fetch', ['payment_schedule_id' => $item->payment_schedule_id, 'archived' => 1]) }}"
                                                ></payment-schedule-line-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </section>
</div>

@endsection