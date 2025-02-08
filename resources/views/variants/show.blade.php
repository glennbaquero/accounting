@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Variant <small>({{ $item->variant_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Variants</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#variant" href="javascript:void(0)" data-toggle="tab">Variant Details</a></li>
                    <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#specification" href="javascript:void(0)" data-toggle="tab">Specification</a></li>
                    <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#details" href="javascript:void(0)" data-toggle="tab">Product Details</a></li>
                    {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="variant">
                        <variant-view
                            submit-url="{{ route('variants.update', $item->id) }}"
                            fetch-url="{{ route('variants.fetch-item', $item->id) }}"
                            show-url ="{{ $showUrl }}"
                            :product="{{ $product_details }}"
                        ></variant-view>
                    </div>
                    <div class="tab-pane" id="specification">
                        <specifications-table
                            fetch-url="{{ route('specifications.fetch', [ 'variant_id' => $item->id ]) }}"
                        ></specifications-table>
                    </div>
                    <div class="tab-pane" id="details">
                        <product-detail-view
                            show-url ="{{ $showUrl }}"
                            :product="{{ $product_details }}"
                        ></product-detail-view>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

@endsection