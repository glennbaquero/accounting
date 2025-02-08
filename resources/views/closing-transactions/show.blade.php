@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Closing Opening Transaction <small></small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('closing-transactions.index') }}">Closing Transations</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <opening-transaction-view
        submit-url="{{ route('closing-transactions.update', $item->id) }}"
        fetch-url="{{ route('closing-transactions.fetch-item', $item->id) }}"
        ></opening-transaction-view>
    </section>
</div>

@endsection