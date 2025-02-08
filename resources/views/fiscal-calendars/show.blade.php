@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Fiscal Calendar <small>({{ $item->fiscal_calendar_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('fiscal-calendars.index') }}">Fiscal Calendar</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">            
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#fiscal_calendar_information" data-toggle="tab">Fiscal Calendar Information</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#fiscal_period_table" data-toggle="tab">Fiscal Periods</a></li>
                    </ul>
                </div>            
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="fiscal_calendar_information">                
                            <fiscal-calendar-view
                                submit-url="{{ route('fiscal-calendars.update', $item->id) }}"
                                fetch-url="{{ route('fiscal-calendars.fetch-item', $item->id) }}"
                            ></fiscal-calendar-view>
                        </div>
                        <div class="tab-pane" id="fiscal_period_table">
                            <div class="mb-4">                            
                                <a href="{{ route('fiscal-periods.create',  $item->id) }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create Fiscal Period
                                </a>
                            </div>                                                        
                            <fiscal-period-table
                                fetch-url="{{ route('fiscal-periods.fetch', ['fiscal_calendar_id' => $item->fiscal_calendar_id]) }}"
                            ></fiscal-period-table>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

@endsection
