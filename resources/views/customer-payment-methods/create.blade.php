@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Methods of Payment - Customer</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-payment-methods.index') }}">Methods of Payment - Customer</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-payment-method-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('customer-payment-methods.fetch-item') }}"
            submit-url="{{ route('customer-payment-methods.store') }}"
        ></customer-payment-method-view>
    </section>
</div>

@endsection