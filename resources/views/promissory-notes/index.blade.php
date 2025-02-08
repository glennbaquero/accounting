@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Promissory Note </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Promissory Note</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <promissory-note-table 
        fetch-url="{{ route('promissory-notes.fetch') }}"
        submit-url="{{ route('promissory-notes.store') }}"
        :cost_centers="{{ $cost_centers }}"
        :departments="{{ $departments }}"
        :expense_purposes="{{ $expense_purposes }}"
        create-url="{{ route('promissory-notes.create') }}"
        create-url="{{ route('promissory-notes.create') }}"
        status-update-url="{{ route('promissory-notes.header-status-update') }}"
    ></promissory-note-table>
    </section>
</div>

@endsection