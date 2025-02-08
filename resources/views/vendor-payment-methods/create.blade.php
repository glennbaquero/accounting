@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Methods of Payment - Vendor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-payment-methods.index') }}">Methods of Payment - Vendor</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-payment-method-view
            code="{{ $code }}"
            fetch-url="{{ route('vendor-payment-methods.fetch-item') }}"
            submit-url="{{ route('vendor-payment-methods.store') }}"
        ></vendor-payment-method-view>
    </section>
</div>

@endsection