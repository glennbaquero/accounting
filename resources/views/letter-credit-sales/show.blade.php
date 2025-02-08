@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Letter of Credit (Sales) <small>({{ $item->bank_document_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('letter-credit-sales.index') }}">Letter of Credit (Sales)</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <letter-credit-sales-view
            :clients="{{ $clients }}"
            submit-url="{{ route('letter-credit-sales.update', $item->id) }}"
            fetch-url="{{ route('letter-credit-sales.fetch-item', $item->id) }}"
        ></letter-credit-sales-view>
    </section>
</div>

@endsection