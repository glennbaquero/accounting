@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Linked Main Account </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('linked-main-accounts.index') }}"> Linked main account </a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <link-main-account-process
        main-accounts-selected-fetch-url="{{ route('main-accounts.fetch', ['attach' => $item->id]) }}"
        main-accounts-fetch-url="{{ route('main-accounts.fetch', ['detach' => $item->id]) }}"
        fetch-url="{{ route('linked-main-accounts.fetch-item', $item->id) }}"
        submit-url="{{ route('linked-main-accounts.update', $item->id) }}"
        ></link-main-account-process>
    </section>
</div>

@endsection