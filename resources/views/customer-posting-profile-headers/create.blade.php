@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Customer Posting Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-posting-profile-headers.index') }}">customer Posting Profiles</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-posting-profile-header-view
        submit-url="{{ route('customer-posting-profile-headers.store') }}"
        fetch-url="{{ route('customer-posting-profile-headers.fetch-item') }}"
        ></customer-posting-profile-header-view>
    </section>
</div>

@endsection