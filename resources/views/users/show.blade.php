@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update User <small>({{ $item->renderName() }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <user-view
        company="{{ $company }}"
        submit-url="{{ route('users.update', $item->id) }}"
        fetch-url="{{ route('users.fetch-item', $item->id) }}"
        companies-fetch-url="{{ route('users.fetch-item', $item->id) }}"
        permissions-fetch-url="{{ route('permissions.fetch', $item->id) }}"
        permissions-submit-url="{{ route('users.update-permissions', $item->id) }}"
        client-selected-fetch-url="{{ route('clients.fetch', ['attach' => $item->id]) }}"
        client-fetch-url="{{ route('clients.fetch', ['detach' => $item->id]) }}"
        ></user-view>
    </section>
</div>

@endsection