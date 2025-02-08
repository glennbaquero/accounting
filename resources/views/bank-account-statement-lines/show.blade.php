@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Bank Account Statement Line</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-account-statement-lines.index') }}">Bank Account Statement Lines</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $item->statement_line_id }}</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-account-statement-line-view
        statement-id="{{ $item->bank_statement_id }}"
        submit-url="{{ route('bank-account-statement-lines.update', $item->id) }}"
        fetch-url="{{ route('bank-account-statement-lines.fetch-item', $item->id) }}"
        ></bank-account-statement-line-view>
    </section>
</div>

@endsection