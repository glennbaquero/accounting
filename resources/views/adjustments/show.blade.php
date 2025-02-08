@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Adjustments <small>({{ $item->adjustment_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('adjustments.index') }}">Adjustments</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <adjustments-view
            :clients="{{ $clients }}"
            submit-url="{{ route('adjustments.update', $item->id) }}"
            fetch-url="{{ route('adjustments.fetch-item', $item->id) }}"
        ></adjustments-view>
    </section>
</div>

@endsection