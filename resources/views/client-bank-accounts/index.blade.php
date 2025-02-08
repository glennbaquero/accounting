@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Client Bank Accounts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Client Bank Accounts</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('client-bank-accounts.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#client-bank-accounts-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#client-bank-accounts-archived" data-toggle="tab">Archive</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#client-bank-accounts-expired" data-toggle="tab">Expired</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="client-bank-accounts-active">
                            <client-bank-account-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('client-bank-accounts.fetch') }}"
                            ></client-bank-account-table>
                        </div>
                        <div class="tab-pane" id="client-bank-accounts-archived">
                            <client-bank-account-table
                                :clients="{{ $clients }}"
                                create-url="{{ route('client-bank-accounts.create') }}"
                                fetch-url="{{ route('client-bank-accounts.fetch-archive') }}"
                            ></client-bank-account-table>
                        </div>
                        <div class="tab-pane" id="client-bank-accounts-expired">
                            <client-bank-account-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('client-bank-accounts.fetch', ['expired' => 1]) }}"
                            ></client-bank-account-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection