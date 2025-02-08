@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Bank Facility Group</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-facility-groups.index') }}">Bank Facility Groups</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-facility-groups-view
        :clients="{{ $clients }}"
        submit-url="{{ route('bank-facility-groups.store') }}"
        fetch-url="{{ route('bank-facility-groups.fetch-item') }}"
        ></bank-facility-groups-view>
    </section>
</div>

@endsection