@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Purchase Order</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('purchase-orders.index') }}">Purchase Order</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <purchase-order-view
            order-number="{{ $order_number }}"
            :show-confirm-button="false"
            submit-url="{{ route('purchase-orders.store') }}"
            fetch-url="{{ route('purchase-orders.fetch-item') }}"
        ></purchase-order-view>
    </section>
</div>

@endsection