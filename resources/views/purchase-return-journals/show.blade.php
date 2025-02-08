@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Purchase Returm Order Vouchers <small>({{ $item->journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('purchase-return-journals.index') }}">Purchase Returm Orders</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <purchase-return-journal-view
            submit-url="{{ route('purchase-return-journals.update', $item->id) }}"
            voucher-submit-url="{{ route('purchase-return-journals.voucher-create', $item->id) }}"
            fetch-url="{{ route('purchase-return-journals.fetch-item', $item->id) }}"
            fetch-voucher-url="{{ route('purchase-return-journals.fetch-vouchers', ['id' => $item->journal_number]) }}"
            status-update-url="{{ route('purchase-return-journals.voucher-status-update') }}"
            journal-validate-url="{{ route('purchase-return-journals.validate', $item->id) }}"
            voucher-validate-url="{{ route('purchase-return-journals.validate-voucher', $item->id) }}"
            post-url="{{ route('purchase-return-journals.post', $item->id) }}"
            :journal-item="{{ $item }}"
        ></purchase-return-journal-view>

    </section>
</div>

@endsection