@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Chart Of Account - Account structures</h1>

            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('chart-of-accounts.show', $coa_id->id) }}">Back To Chart Of Account </a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <account-structure-coa-view
            :as-id="{{ $as_id }}"
            :coa-id="{{ $coa_id }}"        
            submit-url="{{ route('account-structures.store-coa') }}"
            fetch-url="{{ route('account-structures.fetch-item') }}"
        ></account-structure-coa-view>
    </section>
</div>

@endsection