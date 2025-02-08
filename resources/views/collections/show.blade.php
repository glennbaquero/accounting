@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Collection <small>({{ $item->collection_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('collections.index') }}">Collections</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Collection</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <collection-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('collections.fetch-item', $item->id) }}"
            submit-url="{{ route('collections.update', $item->id) }}"
        ></collection-view>
    </section>
</div>

@endsection