@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Accrual Postings <small></small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('accrual-postings.index') }}">Accrual Postings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <accrual-posting-view
        submit-url="{{ route('accrual-postings.update', $item->id) }}"
        fetch-url="{{ route('accrual-postings.fetch-item', $item->id) }}"
        accrual-period-fetch-url="{{ route('accrual-periods.fetch', ['accrual_id' => $item->id])}}"
        ></accrual-posting-view>
    </section>
</div>

@endsection