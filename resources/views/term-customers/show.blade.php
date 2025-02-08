@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Terms Of Payment - Customer <small>({{ $item->terms_of_payment }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('term-customers.index') }}">Terms</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <term-customer-view
            submit-url="{{ route('term-customers.update', $item->id) }}"
            fetch-url="{{ route('term-customers.fetch-item', $item->id) }}"
        ></term-customer-view>
    </section>
</div>

@endsection