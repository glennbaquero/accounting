@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Bank Reconciliation Journal</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-reconciliation-journals.index') }}">Bank Reconciliation Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Bank Reconciliation Journal</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-reconciliation-journal-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('bank-reconciliation-journals.fetch-item') }}"
            submit-url="{{ route('bank-reconciliation-journals.store') }}"
        ></bank-reconciliation-journal-view>
    </section>
</div>

@endsection