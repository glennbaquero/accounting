@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Customer Bank Account </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Customer Bank Account</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <customer-bank-account-create
            :customers="{{ $customers }}"
        ></customer-bank-account-create>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#customer-bank-account-table-active" href="javascript:void(0)" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a class="nav-link" href="#customer-bank-account-table-archived" href="javascript:void(0)" data-toggle="tab">Archived</a></li>
                        <li class="nav-item"><a class="nav-link" href="#customer-bank-account-table-expired" href="javascript:void(0)" data-toggle="tab">Expired</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="customer-bank-account-table-active">
                            <customer-bank-account-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('customer-bank-accounts.fetch') }}"
                            ></customer-bank-account-table>
                        </div>
                        <div class="tab-pane" id="customer-bank-account-table-archived">
                            <customer-bank-account-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('customer-bank-accounts.fetch-archive') }}"
                            ></customer-bank-account-table>
                        </div>
                        <div class="tab-pane" id="customer-bank-account-table-expired">
                            <customer-bank-account-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('customer-bank-accounts.fetch', ['expired' => 1]) }}"
                            ></customer-bank-account-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection