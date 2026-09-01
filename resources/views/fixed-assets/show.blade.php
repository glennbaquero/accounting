@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Fixed Asset <small> {{ $item->asset_name }}</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('fixed-assets.index') }}">Fixed Assets</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <fixed-asset-view

        item-id="{{ $item->id }}"
        submit-url="{{ route('fixed-assets.update', $item->id) }}"
        fetch-url="{{ route('fixed-assets.fetch-item', $item->id) }}"
        generate-schedule-url="{{ route('fixed-assets.generate-schedule', $item->id) }}"
        post-all-due-url="{{ route('fixed-assets.post-all-due', $item->id) }}"
        dispose-url="{{ route('fixed-assets.dispose', $item->id) }}"
        post-line-url-base="{{ url('fixed-assets/'.$item->id.'/depreciation-lines') }}"
         ></fixed-asset-view>
    </section>
</div>

@endsection
