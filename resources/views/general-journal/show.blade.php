@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">        
        <general-journal-view 
        submit-url="{{ route('general-journal.update', $item->id) }}"
        voucher-submit-url="{{ route('general-journal.voucher-create', $item->id) }}"
        fetch-voucher-pending-url="{{ route('general-journal.fetch-vouchers', ['id' => $item->general_journal_number, 'status' => 'pending']) }}"
        fetch-url="{{ route('general-journal.fetch-item', $item->id) }}"
        status-update-url="{{ route('general-journal.voucher-status-update') }}"
        journal-validate-url="{{ route('general-journal.validate', $item->id) }}"
        voucher-validate-url="{{ route('general-journal.validate-voucher') }}"
        post-url="{{ route('general-journal.post', $item->id) }}"
        generate-accrual-url="{{ route('general-journal.generate-accrual') }}"
        reversal-url="{{ route('general-journal.reverse', $item->id) }}"
        :journal-item="{{ $item }}"
        ></general-journal-view>
    </section>
</div>

@endsection