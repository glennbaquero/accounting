@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Withholding Tax Posting <small>({{ $item->withholding_tax_posting_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('withholding-taxes.index') }}">Withholding Tax Postings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Withholding Tax Posting</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#withholding-tax-header" data-toggle="tab">Withholding Tax Posting Header</a></li>
                        <li class="nav-item"><a class="nav-link" href="#withholding-tax-lines" data-toggle="tab">Withholding Tax Posting Lines</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="withholding-tax-header">
                            <withholding-tax-view
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('withholding-taxes.fetch-item', $item->id) }}"
                                submit-url="{{ route('withholding-taxes.update', $item->id) }}"
                            ></withholding-tax-view>
                        </div>
                        <div class="tab-pane" id="withholding-tax-lines">
                            <div class="mb-4">
                                <withholding-tax-line-create
                                    :clients="{{ $clients }}"
                                    :parent="{{ $item }}"
                                    fetch-url="{{ route('withholding-tax-lines.fetch-item') }}"
                                    submit-url="{{ route('withholding-tax-lines.store') }}"
                                ></withholding-tax-line-create>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#withholding-tax-line-active" data-toggle="tab">Active</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#withholding-tax-line-archived" data-toggle="tab">Archive</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="withholding-tax-line-active">
                                                <withholding-tax-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('withholding-tax-lines.fetch', ['withholding_tax_posting_id' => $item->id]) }}"
                                                ></withholding-tax-line-table>
                                            </div>
                                            <div class="tab-pane" id="withholding-tax-line-archived">
                                                <withholding-tax-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('withholding-tax-lines.fetch', ['withholding_tax_posting_id' => $item->id, 'archived' => 1]) }}"
                                                ></withholding-tax-line-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </section>
</div>

@endsection