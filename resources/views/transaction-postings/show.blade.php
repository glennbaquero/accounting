@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Transaction Posting Line<small> ({{ $item->posting_profile }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('transaction-postings.index') }}">Transaction Posting</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <transaction-posting-view
        submit-url="{{ route('transaction-postings.update', $item->id) }}"
        fetch-url="{{ route('transaction-postings.fetch-item', $item->id) }}"
        :posting-header="{{ $header }}"
        ></transaction-posting-view>
    </section>
</div>

@endsection