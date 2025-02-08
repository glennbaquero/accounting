@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>General Journal <small>({{ $item->general_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-payment-journals.index') }}">General Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <journal-create-update
            submit-url="{{ route('general-journal.update', $item->id) }}"
            invoice-journal-number="general_journal_number"
            invoice-header-title="Journal Header Number"
            fetch-url="{{ route('general-journal.fetch-item', $item->id) }}"
            reversal-url="{{ route('general-journal.reversal', $item->id) }}"
        ></journal-create-update>
    </section>
</div>

@endsection