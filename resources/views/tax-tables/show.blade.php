@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Tax Posting <small>({{ $item->tax_posting_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('tax-tables.index') }}">Tax Postings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Tax Posting</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#tax-table-header" data-toggle="tab">Tax Posting Header</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tax-table-lines" data-toggle="tab">Tax Posting Lines</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="tax-table-header">
                            <tax-view
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('tax-tables.fetch-item', $item->id) }}"
                                submit-url="{{ route('tax-tables.update', $item->id) }}"
                            ></tax-view>
                        </div>
                        <div class="tab-pane" id="tax-table-lines">
                            <div class="mb-4">
                                <tax-line-create
                                    :clients="{{ $clients }}"
                                    :parent="{{ $item }}"
                                    fetch-url="{{ route('tax-table-lines.fetch-item') }}"
                                    submit-url="{{ route('tax-table-lines.store') }}"
                                ></tax-line-create>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#tax-line-active" data-toggle="tab">Active</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tax-line-archived" data-toggle="tab">Archive</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="tax-line-active">
                                                <tax-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('tax-table-lines.fetch', ['tax_id' => $item->id]) }}"
                                                ></tax-line-table>
                                            </div>
                                            <div class="tab-pane" id="tax-line-archived">
                                                <tax-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('tax-table-lines.fetch', ['tax_id' => $item->id, 'archived' => 1]) }}"
                                                ></tax-line-table>
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