@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bank Facility Type <small>({{ $item->bank_facility_type_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-facility-types.index') }}">Bank Facility Type</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-facility-types-view
            :clients="{{ $clients }}"
            submit-url="{{ route('bank-facility-types.update', $item->id) }}"
            fetch-url="{{ route('bank-facility-types.fetch-item', $item->id) }}"
        ></bank-facility-types-view>
    </section>
</div>

@endsection