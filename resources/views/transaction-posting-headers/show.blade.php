@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Transaction Posting <small>({{ $item->posting_profile }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('transaction-posting-headers.index') }}">Transaction Postings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <transaction-posting-header-view
            submit-url="{{ route('transaction-posting-headers.update', $item->id) }}"
            fetch-url="{{ route('transaction-posting-headers.fetch-item', $item->id) }}"
            create-posting-line-url="{{ route('transaction-postings.create', $item->id) }}"
            posting-lines-fetch-url="{{ route('transaction-postings.fetch', ['header' => $item->id]) }}"
        ></transaction-posting-header-view>
    </section>
</div>

@endsection