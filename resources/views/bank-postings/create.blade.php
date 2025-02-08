@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Bank Posting</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-postings.index') }}">Bank Postings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Bank Posting</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-posting-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('bank-postings.fetch-item') }}"
            submit-url="{{ route('bank-postings.store') }}"
        ></bank-posting-view>
    </section>
</div>

@endsection