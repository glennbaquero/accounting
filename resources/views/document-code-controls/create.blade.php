@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Document Code Control</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('document-code-controls.index') }}"> Document Code Controls</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <document-code-control-view
        submit-url="{{ route('document-code-controls.store') }}"
        fetch-url="{{ route('document-code-controls.fetch-item') }}"
        ></document-code-control-view>
    </section>
</div>

@endsection