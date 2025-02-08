@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Payment Reversal</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('payment-reversals.index') }}">Payment Reversals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Payment Reversal</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <payment-reversal-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('payment-reversals.fetch-item') }}"
            submit-url="{{ route('payment-reversals.store') }}"
        ></payment-reversal-view>
    </section>
</div>

@endsection