@extends('master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Invoice no. 1001</h1>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <invoice-view
            upload-url="https://www.google.com/"
        ></invoice-view>
    </section>
</div>

@endsection