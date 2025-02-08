@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row ">
            <div class="col-sm-6">
                <h1>Update Ledgers <small>({{ $item->ledger_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ route('ledgers.index') }}">Ledger</a></li>
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
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#ledger_information" data-toggle="tab">Ledger Information</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#account_structure" data-toggle="tab">Account Structure</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="ledger_information">
                            <ledger-view
                                submit-url="{{ route('ledgers.update', $item->id) }}"
                                fetch-url="{{ route('ledgers.fetch-item', $item->id) }}"
                            ><ledger-view>
                        </div>
                        <div class="tab-pane" id="account_structure">                
                            <div class="mb-4">
                                <a href="{{ route('account-structures.create-ledger', $item->ledger_id) }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create Ledgers - Account Structures
                                </a>
                            </div>                                    
                            <account-structure-table 
                                ref="table-2" 
                                data-table-id="table-2"                                        
                                fetch-url="{{ route('account-structures-ledger.fetch', ['ledger_id' => $item->ledger_id]) }}"
                            ></account-structure-table>
                        </div>                  

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection