@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Recurring Journal Template</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('recurring-journal-templates.index') }}">Recurring Journal Templates</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <recurring-journal-template-view
        template-id="{{ $template_id }}"
        submit-url="{{ route('recurring-journal-templates.store') }}"
        fetch-url="{{ route('recurring-journal-templates.fetch-item') }}"
        ></recurring-journal-template-view>
    </section>
</div>

@endsection
