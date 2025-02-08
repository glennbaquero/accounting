@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Chart Of Account - Main account </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('chart-of-accounts.show', $coa_id->id) }}">Chart-Of-Account - Main account</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <main-account-coa-view
            :coa-id="{{ $coa_id }}"        
            submit-url="{{ route('main-accounts.store') }}"
            fetch-url="{{ route('main-accounts.fetch-item') }}"
        ></main-account-coa-view>
    </section>
</div>

@endsection