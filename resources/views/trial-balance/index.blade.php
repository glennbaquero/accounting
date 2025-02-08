@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Trial Balance </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Trial Balance</a></li>
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
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="active">
                            <trial-balance-table 
                                fetch-url="{{ route('trial-balance.fetch') }}"
                                ref="table-1"
                            ></trial-balance-table>
                        </div>
                        <div class="tab-pane" id="archived">
                            <trial-balance-table 
                                fetch-url="{{ route('trial-balance.fetch-archive') }}"
                                ref="table-2"
                                disabled
                            ></trial-balance-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection