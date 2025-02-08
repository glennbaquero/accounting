@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Accrual Posting</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('accrual-postings.index') }}">Accrual Posting</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <accrual-posting-view
        id="{{ $id }}"
        submit-url="{{ route('accrual-postings.store') }}"
        fetch-url="{{ route('accrual-postings.fetch-item') }}"
        ></accrual-posting-view>
    </section>
</div>

@endsection