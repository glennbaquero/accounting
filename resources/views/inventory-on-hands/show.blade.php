@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Inventory On Hand <small>({{ $item->inventory_on_hand_number }})</small></h1>
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
        

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#ioh" href="javascript:void(0)" data-toggle="tab">Inventory On Hand</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#purchase" href="javascript:void(0)" data-toggle="tab">Purchase</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#sales" href="javascript:void(0)" data-toggle="tab">Sales</a></li>
                        {{-- <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#drafts" data-toggle="tab">Drafts</a></li> --}}
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="ioh">
                            <inventory-on-hand-view
                                submit-url="{{ route('inventory-on-hands.update', $item->id) }}"
                                fetch-url="{{ route('inventory-on-hands.fetch-item', $item->id) }}"
                            ></inventory-on-hand-view>
                        </div>
                        <div class="tab-pane" id="purchase">
                            <order-line-table 
                            ref="table-2"
                            fetch-url="{{ route('purchase-order-lines.fetch', ['variant' => $item->item_number] ) }}"
                            line-number="purchase_order_line_number"
                            order-number="purchase_order_number"
                            type="Purchase"
                            user="Vendor"
                            user-type="vendor"
                            ></order-line-table>
                        </div>
                        <div class="tab-pane" id="sales">
                            <order-line-table 
                            ref="table-3"
                            fetch-url="{{ route('sales-order-lines.fetch', ['variant' => $item->item_number] ) }}"
                            line-number="sales_order_line_number"
                            order-number="sales_order_number"
                            type="Sales"
                            user="Customer"
                            user-type="customer"
                            ></order-line-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

@endsection