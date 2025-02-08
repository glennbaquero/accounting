@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Customer Payment Fee Setup <small>({{ $item->fee_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-payment-fee-setups.index') }}">Customer Payment Fee Setup</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#customer-payment-fee-setup" data-toggle="tab">Customer Payment Fee Setup Information</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="customer-payment-fee-setup">
                            <customer-payment-fee-setup-view
                                submit-url="{{ route('customer-payment-fee-setups.update', $item->id) }}"
                                fetch-url="{{ route('customer-payment-fee-setups.fetch-item', $item->id) }}"
                            ></customer-payment-fee-setup-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection