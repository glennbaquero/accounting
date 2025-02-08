@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Promissory Notes <small>({{ $item->promissory_note }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('purchase-promissory-notes.index') }}">Promissory Notes</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Promissory Notes</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <purchase-promissory-note-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('purchase-promissory-notes.fetch-item', $item->id) }}"
            submit-url="{{ route('purchase-promissory-notes.update', $item->id) }}"
        ></purchase-promissory-note-view>
    </section>
</div>

@endsection