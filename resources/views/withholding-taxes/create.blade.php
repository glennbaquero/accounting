@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Withholding Tax Posting</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('withholding-taxes.index') }}">Withholding Tax Postings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Withholding Tax Posting</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <withholding-tax-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('withholding-taxes.fetch-item') }}"
            submit-url="{{ route('withholding-taxes.store') }}"
        ></withholding-tax-view>
    </section>
</div>

@endsection