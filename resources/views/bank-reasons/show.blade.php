@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bank Reason <small>({{ $item->reason_code }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-reasons.index') }}">Bank Reasons</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Bank Reason</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-reason-view
            fetch-url="{{ route('bank-reasons.fetch-item', $item->id) }}"
            submit-url="{{ route('bank-reasons.update', $item->id) }}"
        ></bank-reason-view>
    </section>
</div>

@endsection