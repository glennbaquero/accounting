@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Ledger Calendar <small> {{ $item->ledger_calendar_name }}</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ledger-calendars.index') }}">Ledger Calendars</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <ledger-calendar-view       

        submit-url="{{ route('ledger-calendars.update', $item->id) }}"
        fetch-url="{{ route('ledger-calendars.fetch-item', $item->id) }}"        
         ></ledger-calendar-view>
    </section>
</div>

@endsection