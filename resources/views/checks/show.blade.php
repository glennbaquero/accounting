@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Check <small>({{ $item->check_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('checks.index') }}">Check</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <check-view
            :clients="{{ $clients }}"
            submit-url="{{ route('checks.update', $item->id) }}"
            fetch-url="{{ route('checks.fetch-item', $item->id) }}"
            vendor-payments-approval="{{ route('vendor-payments.fetch',  ['pending' => 0, 'check_number' => $item->check_number ]) }}"
            vendor-payments-approved="{{ route('vendor-payments.fetch',  ['approved_payment' => 1, 'check_number' => $item->check_number ] ) }}"
            vendor-payments-posted="{{ route('vendor-payments.fetch',  ['posted_payment' => 1, 'check_number' => $item->check_number ] ) }}"
            customer-payments-approval="{{ route('customer-payments.fetch',  ['pending' => 0, 'check_number' => $item->check_number ]) }}"
            customer-payments-approved="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'check_number' => $item->check_number ] ) }}"
            customer-payments-posted="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'check_number' => $item->check_number ] ) }}"
        ></check-view>
    </section>
</div>

@endsection