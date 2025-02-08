@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Payment Cancellation Journal</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('payment-cancellation-journals.index') }}">Payment Cancellation Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Payment Cancellation Journal</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <payment-cancellation-journal-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('payment-cancellation-journals.fetch-item') }}"
            submit-url="{{ route('payment-cancellation-journals.store') }}"
        ></payment-cancellation-journal-view>
    </section>
</div>

@endsection