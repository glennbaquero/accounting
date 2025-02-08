@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Promissory Note</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('promissory-notes.index') }}">Promissory Note</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <promissory-note-create-update
            submit-url="{{ route('promissory-notes.store') }}"
            invoice-journal-number="promissory_note_journal_number"
            invoice-header-title="Journal Header Number"
            fetch-url="{{ route('promissory-notes.fetch-item') }}"
        ></promissory-note-create-update>
    </section>
</div>

@endsection