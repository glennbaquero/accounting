@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Client Bank Account</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Bank Account</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <client-bank-account-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('client-bank-accounts.fetch-item') }}"
            submit-url="{{ route('client-bank-accounts.store') }}"
        ></client-bank-account-view>
    </section>
</div>

@endsection