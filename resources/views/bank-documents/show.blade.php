@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bank Document <small>({{ $item->bank_facility_agreement_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-documents.index') }}">Bank Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-documents-view
            :clients="{{ $clients }}"
            submit-url="{{ route('bank-documents.update', $item->id) }}"
            fetch-url="{{ route('bank-documents.fetch-item', $item->id) }}"
        ></bank-documents-view>
    </section>
</div>

@endsection