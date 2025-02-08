@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Interest Adjustment <small>({{ $item->interest_adjustment_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('interest-adjustments.index') }}">Interest Adjustments</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Interest Adjustment</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <interest-adjustment-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('interest-adjustments.fetch-item', $item->id) }}"
            submit-url="{{ route('interest-adjustments.update', $item->id) }}"
        ></interest-adjustment-view>
    </section>
</div>

@endsection