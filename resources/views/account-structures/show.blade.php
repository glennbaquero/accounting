@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Account Structure ({{ $item->ledger_account_structure_name}})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('account-structures.index') }}">Account Structure</a></li>
                    <li class="breadcrumb-item"><a href="">{{ $item->ledger_account_structure_name}}</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <account-structure-view
            submit-url="{{ route('account-structures.update', $item->id) }}"
            fetch-url="{{ route('account-structures.fetch-item', $item->id) }}"
        ></account-structure-view>
    </section>
</div>

@endsection