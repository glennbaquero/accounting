@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Budget <small> {{ $item->budget_name }}</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('budgets.index') }}">Budgets</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <budget-view

        submit-url="{{ route('budgets.update', $item->id) }}"
        fetch-url="{{ route('budgets.fetch-item', $item->id) }}"
        variance-url="{{ route('budgets.variance', $item->id) }}"
         ></budget-view>
    </section>
</div>

@endsection
