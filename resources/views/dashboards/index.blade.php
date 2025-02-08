@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-sm-9">
                <h1> Dashboard </h1>
            </div>
            <div class="col-sm-3">
                <client-select class="mt-1"></client-select>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <dashboard></dashboard>
        </div>
    </section>
</div>

@endsection