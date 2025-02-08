@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Customer Payment Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-payment-fees.index') }}">Customer Payment Fee</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-payment-fee-view
        submit-url="{{ route('customer-payment-fees.store') }}"
        fetch-url="{{ route('customer-payment-fees.fetch-item') }}"
        ></customer-payment-fee-view>
    </section>
</div>

@endsection