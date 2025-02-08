@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Vendor Posting Profile <small>({{ $item->posting_profile }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-posting-profile-headers.index') }}">Vendor Posting Profiles</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-posting-profile-header-view
            submit-url="{{ route('vendor-posting-profile-headers.update', $item->id) }}"
            fetch-url="{{ route('vendor-posting-profile-headers.fetch-item', $item->id) }}"
            create-posting-line-url="{{ route('vendor-posting-profiles.create', $item->id) }}"
            posting-lines-fetch-url="{{ route('vendor-posting-profiles.fetch', ['header' => $item->id]) }}"
        ></vendor-posting-profile--header-view>
    </section>
</div>

@endsection