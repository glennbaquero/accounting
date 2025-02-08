@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> General Journals </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">General Journals</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <general-journal-table 
            fetch-url="{{ route('general-journal.fetch') }}"
            submit-url="{{ route('general-journal.store') }}"
            :cost_centers="{{ $cost_centers }}"
            :departments="{{ $departments }}"
            :expense_purposes="{{ $expense_purposes }}"
            :clients="{{ $clients }}"
            create-url="{{ route('general-journal.create') }}"
            status-update-url="{{ route('general-journal.header-status-update') }}"
        ></general-journal-table>
    </section>
</div>

@endsection