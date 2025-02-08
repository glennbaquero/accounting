@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Inventory Journal Vouchers <small>({{ $item->inventory_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('inventory-journals.index') }}">Inventory Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <inventory-journal-view
            submit-url="{{ route('inventory-journals.update', $item->id) }}"
            voucher-submit-url="{{ route('inventory-journals.voucher-create', $item->id) }}"
            fetch-url="{{ route('inventory-journals.fetch-item', $item->id) }}"
            fetch-voucher-url="{{ route('inventory-journals.fetch-vouchers', ['id' => $item->inventory_journal_number]) }}"
            status-update-url="{{ route('inventory-journals.voucher-status-update') }}"
            journal-validate-url="{{ route('inventory-journals.validate', $item->id) }}"
            voucher-validate-url="{{ route('inventory-journals.validate-voucher', $item->id) }}"
            post-url="{{ route('inventory-journals.post', $item->id) }}"
            :journal-item="{{ $item }}"
        ></inventory-journal-view>

    </section>
</div>

@endsection