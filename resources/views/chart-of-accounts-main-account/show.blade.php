@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Chart Of Account 
{{--                     <small> on ({{ $fiscal_calendar_id->fiscal_calendar_code }})</small> --}}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('fiscal-calendars.show', $item->parent->id) }}">Fiscal Period </a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <fiscal-period-view
            submit-url="{{ route('fiscal-periods.update', $item->id) }}"
            fetch-url="{{ route('fiscal-periods.fetch-item', $item->id) }}"
            :fc-id="{{ $fiscal_calendar_id }}"
        ></fiscal-period-view>
    </section>
</div>

@endsection