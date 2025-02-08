@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Vendor Payment Fee Setup</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-payment-fee-setups.index') }}">Vendor Payment Fee Setup</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-payment-fee-setup-view
        submit-url="{{ route('vendor-payment-fee-setups.store') }}"
        fetch-url="{{ route('vendor-payment-fee-setups.fetch-item') }}"
        ></vendor-payment-fee-setup-view>
    </section>
</div>

@endsection