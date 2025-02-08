@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Bank Reconciliations</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Bank Reconciliations</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#bank-reconciliation-adjustments" data-toggle="tab">Bank Reconciliation Adjustments</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="bank-reconciliation-adjustments">
                            <bank-reconciliation-form
                                :client-banks="{{ $client_banks }}"
                                :bank-statements="{{ $bank_statements }}"
                                statement-line-url="{{ route('bank-account-statement-lines.fetch', ['reconciled' => false]) }}"
                                statement-line-adjustment-url="{{ route('bank-account-statement-line-adjustments.fetch', ['reconciled' => false]) }}"
                                cash-register-url="{{ route('cashflow-transactions.fetch', ['reconciled' => false]) }}"
                                cash-adjustment-url="{{ route('cashflow-transaction-adjustments.fetch', ['reconciled' => false]) }}"
                                generate-cash-register-url="{{ route('bank-reconciliations.generate-cash-register') }}"
                                generate-match-url="{{ route('bank-reconciliations.generate-match') }}"
                                generate-adjustments-url="{{ route('bank-reconciliations.generate-adjustments') }}"
                                generate-reconciliation-url="{{ route('bank-reconciliations.generate-reconciliation') }}"
                            ></bank-reconciliation-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection