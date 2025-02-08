@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Chart of Account</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('chart-of-accounts.index') }}">Chart of Account</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <chart-of-account-view
        coa-id="{{ $coa_id }}"
        submit-url="{{ route('chart-of-accounts.store') }}"
        fetch-url="{{ route('chart-of-accounts.fetch-item') }}"
        ></chart-of-account-view>
    </section>
</div>

@endsection