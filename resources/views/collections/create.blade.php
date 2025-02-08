@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Collection</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('collections.index') }}">Collection</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Collection</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <collection-view
            :clients="{{ $clients }}"
            :boe="{{ $boe }}"
            fetch-url="{{ route('collections.fetch-item') }}"
            submit-url="{{ route('collections.store') }}"
        ></collection-view>
    </section>
</div>

@endsection