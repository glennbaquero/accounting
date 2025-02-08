@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Promissory Note</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('purchase-promissory-notes.index') }}">Promissory Note</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Bills of Exchange</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <purchase-promissory-note-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('purchase-promissory-notes.fetch-item') }}"
            submit-url="{{ route('purchase-promissory-notes.store') }}"
        ></purchase-promissory-note-view>
    </section>
</div>

@endsection