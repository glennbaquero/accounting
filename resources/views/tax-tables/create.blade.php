@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Tax Posting</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('tax-tables.index') }}">Tax Postings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Tax Posting</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <tax-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('tax-tables.fetch-item') }}"
            submit-url="{{ route('tax-tables.store') }}"
        ></tax-view>
    </section>
</div>

@endsection