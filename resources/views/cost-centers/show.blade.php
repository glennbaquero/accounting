@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Cost Center <small>({{ $item->name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('cost-centers.index') }}">Cost Center</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <cost-center-view
            submit-url="{{ route('cost-centers.update', $item->id) }}"
            fetch-url="{{ route('cost-centers.fetch-item', $item->id) }}"
        ></cost-center-view>
    </section>
</div>

@endsection