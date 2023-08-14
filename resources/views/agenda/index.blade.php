@extends('layouts.app')

@section('title')
    Agenda
@endsection

@section('breadcrumb')
<div class="pagetitle">
    <h1>Agenda</h1>
    <nav>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Agenda</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row m-4">
                        <div class="d-flex">
                            <div class="col-sm-6 col-md-6">
                                @if (Auth::check() == true && Auth::user()->username != 'superadmin')
                                <button class="btn btn-outline-primary" id="btn-add" type="button">
                                    <i class="bx bx-plus"></i>
                                    Tambah Agenda
                                </button>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <form id="form-filter">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari berdasarkan nama/judul agenda" id="filter_judul" name="filter_judul" autocomplete="off">
                                        <button class="input-group-text" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar" class="mt-4 m-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('agenda.modal-add')
@include('agenda.modal-detail')
@include('agenda.edit')
@endsection
@section('script')
    @include('agenda.script')
@endsection