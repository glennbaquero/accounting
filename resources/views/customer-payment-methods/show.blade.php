@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Methods of Payment - Customer <small>({{ $item->method_of_payment_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-payment-methods.index') }}">Update Methods of Payment - Customer</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-payment-method-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('customer-payment-methods.fetch-item', $item->id) }}"
            submit-url="{{ route('customer-payment-methods.update', $item->id) }}"
        ></customer-payment-method-view>
    </section>
</div>

@endsection