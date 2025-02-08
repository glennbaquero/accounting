@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row ">
            <div class="col-sm-6">
                <h1>Update Chart of Account <small>({{ $item->coa_name }})</small> </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ route('chart-of-accounts.index') }}">Back To Chart of accounts</a></li>
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
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#coa_information" data-toggle="tab">Chart Of Account Information</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#main_account_table" data-toggle="tab">Main Accounts</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#account_structure" data-toggle="tab">Account Structure</a></li>
                        {{--  <li class="nav-item"><a @click="initList('table-4')" class="nav-link" href="#fiscal_calendar" data-toggle="tab">Fiscal Calendar</a></li>                         --}}
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="coa_information">
                            <chart-of-account-view
                                submit-url="{{ route('chart-of-accounts.update', $item->id) }}"
                                fetch-url="{{ route('chart-of-accounts.fetch-item', $item->id) }}"
                            ></chart-of-account-view>
                        </div>
                        <div class="tab-pane" id="main_account_table">                
                            <div class="mb-4">
                                <a href="{{ route('main-accounts.create-coa', $item->coa_id) }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create Chart Of Account - Main Account
                                </a>
                            </div>                                    
                            <chart-of-accounts-main-account-table 
                                fetch-url="{{ route('main-accounts-coa.fetch', ['coa_id' => $item->id]) }}"
                                ref="table-2" 
                                data-table-id="table-2"                                
                            ></chart-of-accounts-main-account-table>
                        </div>
                        <div class="tab-pane" id="account_structure">
                            <div class="mb-4">
                                <a href="{{ route('account-structures.create-coa', $item->coa_id) }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create Chart Of Account - Account Structure
                                </a>
                            </div>                              
                            <chart-of-accounts-account-structure-table
                                ref="table-3" 
                                data-table-id="table-3"
                                fetch-url="{{ route('account-structures-coa.fetch', ['coa_id' => $item->coa_id]) }}"
                            ></chart-of-accounts-account-structure-table>
                        </div>
{{--                         <div class="tab-pane" id="fiscal_calendar">
                            <div class="mb-4">
                                <a href="{{ route('fiscal-calendars.create', $item->id) }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create Fiscal Calendar
                                </a>
                            </div>                            
                            <fiscal-calendar-table
                                fetch-url="{{ route('fiscal-calendars.fetch', ['coa_id' => $item->coa_id]) }}"                                
                            ></fiscal-calendar-table>
                        </div> --}}                        

                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection