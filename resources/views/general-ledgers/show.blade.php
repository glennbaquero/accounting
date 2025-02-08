@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update General Ledger <small>{{ $item->name }}</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('general-ledgers.index') }}">General Ledgers</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <general-ledger-view
            submit-url="{{ route('general-ledgers.update', $item->id) }}"
            fetch-url="{{ route('general-ledgers.fetch-item', $item->id) }}"
            fetch-url-journal-line="{{ route('general-ledger-lines.fetch', ['general_ledger_id' => $item->id]) }}"
            fetch-url-vendor-line="{{ route('general-ledger-lines.fetch', ['line_type' => 'Vendor','general_ledger_id' => $item->id]) }}"
            fetch-url-customer-line="{{ route('general-ledger-lines.fetch', ['line_type' => 'Customer','general_ledger_id' => $item->id]) }}"
            fetch-url-general-ledger-summary="{{ route('general-ledger-lines.general-ledger-summary') }}"
            fetch-url-adjusted-trial-balance="{{ route('general-ledger-lines.adjusted-trial-balance') }}"
            fetch-url-unadjusted-trial-balance="{{ route('general-ledger-lines.unadjusted-trial-balance') }}"
            fetch-url-post-closing-trial-balance="{{ route('general-ledger-lines.post-closing-trial-balance') }}"
            generate-closing-transaction-url="{{ route('general-ledgers-generate-closing-transaction', $item->id) }}"
            enable-closing-transaction-url="{{ route('general-ledgers-enable-closing-transaction', $item->id) }}"
            set-password-url="{{ route('closing-transactions.set-password') }}"
        ></general-ledger-view>
    </section>
</div>

@endsection

