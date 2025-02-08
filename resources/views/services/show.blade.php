@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Service <small>({{ $item->name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Service</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('service-tasks.create', $item->id) }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create Service Task
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#service" data-toggle="tab">Service Information</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#service_task" data-toggle="tab">Service Task</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="service">
                            <service-view
                                submit-url="{{ route('services.update', $item->id) }}"
                                fetch-url="{{ route('services.fetch-item', $item->id) }}"
                            ></service-view>
                        </div>
                        <div class="tab-pane" id="service_task">
                            <service-task-table
                                ref="table-2"
                                fetch-url="{{ route('service-tasks.fetch', ['service' => $item->id]) }}"
                            ></service-task-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection