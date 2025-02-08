@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chart-Of-Accounts -  Main account </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="javascript:history.go(-1)">Chart-Of-Accounts - Main account </a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">
{{--                 <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#mac_information" data-toggle="tab">Main account  Information</a></li>
                    </ul>
                </div> --}}

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="mac_information">
                            <main-account-view
                                submit-url="{{ route('main-accounts.update', $item->id) }}"
                                fetch-url="{{ route('main-accounts.fetch-item', $item->id) }}"
                            ></main-account-view>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection