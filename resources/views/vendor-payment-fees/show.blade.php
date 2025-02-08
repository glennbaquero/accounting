@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Vendor Payment Fee <small>({{ $item->name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-payment-fees.index') }}">Vendor Payment Fee</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#vendor-payment-fee" data-toggle="tab">Depoit Slip Information</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="vendor-payment-fee">
                            <vendor-payment-fee-view
                                submit-url="{{ route('vendor-payment-fees.update', $item->id) }}"
                                fetch-url="{{ route('vendor-payment-fees.fetch-item', $item->id) }}"
                            ></vendor-payment-fee-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection