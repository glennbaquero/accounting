@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Letter of Credit (Purchase)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('letter-credit-purchases.index') }}">Letter of Credit (Purchase)</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <letter-credit-purchases-view
        :clients="{{ $clients }}"
        submit-url="{{ route('letter-credit-purchases.store') }}"
        fetch-url="{{ route('letter-credit-purchases.fetch-item') }}"
        ></letter-credit-purchases-view>
    </section>
</div>

@endsection