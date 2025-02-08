@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Journal Voucher <small>({{ $item->promissory_note_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('promissory-notes.index') }}">Promissory Note</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Vouchers</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <promissory-note-view
            submit-url="{{ route('promissory-notes.update', $item->id) }}"
            voucher-submit-url="{{ route('promissory-notes.voucher-create', $item->id) }}"
            fetch-url="{{ route('promissory-notes.fetch-item', $item->id) }}"
            :journal-item="{{ $item }}"

            fetch-voucher-url="{{ route('promissory-notes.fetch-vouchers', ['id' => $item->promissory_note_journal_number]) }}"
            status-update-url="{{ route('promissory-notes.voucher-status-update') }}"
            journal-validate-url="{{ route('promissory-notes.validate', $item->id) }}"
            voucher-validate-url="{{ route('promissory-notes.validate-voucher') }}"
            post-url="{{ route('promissory-notes.post', $item->id) }}"
        ></promissory-note-view>
    </section>
</div>

@endsection