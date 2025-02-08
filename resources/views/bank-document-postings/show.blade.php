@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bank Document Posting <small>({{ $item->bank_document_postings }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-document-postings.index') }}">Bank Document Posting</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-document-postings-view
            :clients="{{ $clients }}"
            submit-url="{{ route('bank-document-postings.update', $item->id) }}"
            fetch-url="{{ route('bank-document-postings.fetch-item', $item->id) }}"
        ></bank-document-postings-view>
    </section>
</div>

@endsection