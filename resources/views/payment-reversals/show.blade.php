@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Payment Reversal <small>({{ $item->payment_reversal_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('payment-reversals.index') }}">Payment Reversals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Payment Reversal</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <payment-reversal-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('payment-reversals.fetch-item', $item->id) }}"
            submit-url="{{ route('payment-reversals.update', $item->id) }}"
        ></payment-reversal-view>
    </section>
</div>

@endsection