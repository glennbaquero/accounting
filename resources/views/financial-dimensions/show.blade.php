@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Financial Dimension <small>({{ $item->dimension_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financial-dimensions.index') }}">Financial Dimensions</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#info" href="javascript:void(0)" data-toggle="tab">Information</a></li>
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link" href="#value" href="javascript:void(0)" data-toggle="tab">Financial dimension values</a></li>
                        {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="info">
                            <financial-dimension-view
                                submit-url="{{ route('financial-dimensions.update', $item->id) }}"
                                fetch-url="{{ route('financial-dimensions.fetch-item', $item->id) }}"
                            ></financial-dimension-view>
                        </div>
                        <div class="tab-pane" id="value">
                            <div class="mb-4">
                                <a href="{{ route('financial-dimension-values.create', $item->financial_dimension) }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create financial dimension value
                                </a>
                            </div>
                            
                            <financial-dimension-value-table
                                ref="table-1"
                                disabled
                                fetch-url="{{ route('financial-dimension-values.fetch', ['financial_dimension' => $item->financial_dimension]) }}"
                            ></financial-dimension-value-table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
</div>

@endsection