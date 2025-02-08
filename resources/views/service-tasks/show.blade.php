@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Service Task<small>({{ $item->service }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('services.show', $service->id) }}">Service Task</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <service-task-view
            :service="{{ $service }}"
            submit-url="{{ route('service-tasks.update', $item->id) }}"
            fetch-url="{{ route('service-tasks.fetch-item', $item->id) }}"
        ></service-task-view>
    </section>
</div>

@endsection