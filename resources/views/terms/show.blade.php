@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Terms Of Payment - Vendor <small>({{ $item->terms_of_payment }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('terms.index') }}">Terms</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <term-view
            submit-url="{{ route('terms.update', $item->id) }}"
            fetch-url="{{ route('terms.fetch-item', $item->id) }}"
        ></term-view>
    </section>
</div>

@endsection