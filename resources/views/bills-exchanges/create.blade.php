@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Bills of Exchange</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bills-exchanges.index') }}">Bills of Exchange</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Bills of Exchange</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bills-exchange-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('bills-exchanges.fetch-item') }}"
            submit-url="{{ route('bills-exchanges.store') }}"
        ></bills-exchange-view>
    </section>
</div>

@endsection