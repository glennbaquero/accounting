@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Main account categories </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('main-accounts-categories.index') }}">Main account categories</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <main-account-category-form
        submit-url="{{ route('main-accounts-categories.update', $item->id) }}"
        fetch-url="{{ route('main-accounts-categories.fetch-item', $item->id) }}"
        ></main-account-category-form>
    </section>
</div>

@endsection