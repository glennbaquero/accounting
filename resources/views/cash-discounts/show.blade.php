@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Cash Discount <small>({{ $item->next_discount_code }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('cash-discounts.index') }}">Cash Discount</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <cash-discount-view
        submit-url="{{ route('cash-discounts.update', $item->id) }}"
        fetch-url="{{ route('cash-discounts.fetch-item', $item->id) }}"
        ></cash-discount-view>
    </section>
</div>

@endsection