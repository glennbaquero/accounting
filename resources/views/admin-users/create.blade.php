@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create {{ $type == 'company-admin' ? 'Company Admin' : 'System Admin' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin-users.index') }}">{{ $type == 'company-admin' ? 'Company Admin' : 'System Admin' }}</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <admin-user-view
        form-type="{{ $type }}"
        submit-url="{{ route('admin-users.store') }}"
        fetch-url="{{ route('admin-users.fetch-item') }}"
        ></admin-user-view>
    </section>
</div>

@endsection