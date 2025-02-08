@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Fiscal Periods</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('fiscal-calendars.show', $fiscal_calendar_id->id) }}">Back</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <fiscal-period-view
            submit-url="{{ route('fiscal-periods.store') }}"
            fetch-url="{{ route('fiscal-periods.fetch-item') }}"
            :fc-id="{{ $fiscal_calendar_id }}"
            :fp-id = "{{ $fp_id }}"
         ></fiscal-period-view>
    </section>
</div>

@endsection