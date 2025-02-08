@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Financial Dimension Value <small> on ({{ $item->dimension_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financial-dimensions.show', $item->parent->id) }}">Financial Dimension Values</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <financial-dimension-value-view
            submit-url="{{ route('financial-dimension-values.update', $item->id) }}"
            fetch-url="{{ route('financial-dimension-values.fetch-item', $item->id) }}"
            :dimension-value="{{ $financial_dimension }}"
        ></financial-dimension-value-view>
    </section>
</div>

@endsection