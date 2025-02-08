@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Interest Setup</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('interest-setups.index') }}">Interest Setup</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Interest Setup</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <interest-setup-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('interest-setups.fetch-item') }}"
            submit-url="{{ route('interest-setups.store') }}"
        ></interest-setup-view>
    </section>
</div>

@endsection