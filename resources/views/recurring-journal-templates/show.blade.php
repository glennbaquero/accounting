@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Recurring Journal Template <small> {{ $item->template_name }}</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('recurring-journal-templates.index') }}">Recurring Journal Templates</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <recurring-journal-template-view

        submit-url="{{ route('recurring-journal-templates.update', $item->id) }}"
        fetch-url="{{ route('recurring-journal-templates.fetch-item', $item->id) }}"
        pause-url="{{ route('recurring-journal-templates.pause', $item->id) }}"
        resume-url="{{ route('recurring-journal-templates.resume', $item->id) }}"
        run-now-url="{{ route('recurring-journal-templates.run-now', $item->id) }}"
         ></recurring-journal-template-view>
    </section>
</div>

@endsection
