@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Interest Calculation</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('interest-calculations.index') }}">Interest Calculation</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Interest Calculation</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <interest-calculation-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('interest-calculations.fetch-item') }}"
            submit-url="{{ route('interest-calculations.store') }}"
        ></interest-calculation-view>
    </section>
</div>

@endsection