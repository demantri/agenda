@extends('layouts.app')

@section('title')
    Manajemen Unit
@endsection

@section('breadcrumb')
<div class="pagetitle">
    <h1>Manajemen Unit</h1>
    <nav>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Unit</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <br>
                    <table class="table table-hover" id="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Action</th>
                                <th class="text-nowrap">No</th>
                                <th class="text-nowrap">Unit/Bagian</th>
                                <th class="text-nowrap">Warna Agenda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data as $item)
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <button 
                                        style="margin-right: 3px;" 
                                        class="btn btn-sm btn-light btn-edit"
                                        data-bs-target="#edit"
                                        data-bs-toggle="modal"
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama }}"
                                        data-color="{{ $item->color }}"
                                        ><i class="ri-pencil-fill"></i></button>
                                        <a href="{{ url('unit/delete/' . $item->id ) }}" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-5-line"></i></a>
                                    </div>
                                </td>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->color }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>Input Form</h5>
                </div>
                <form action="{{ url('unit/store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-line mb-2">
                            <label class="col-form-label" for="nama">Nama unit/bagian</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Input unit/bagian" required>
                        </div>
                        <div class="form-line mb-2">
                            <label class="col-form-label" for="color">Warna Agenda</label>
                            <input type="color" class="form-control form-control-color" id="color" name="color" placeholder="Input warna agenda" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="ri-save-3-line"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@include('unit.edit')

@endsection
@section('script')
    <script>
        @if(Session::has('success'))
            toastr.options = {
                "closeButton" : true,
                "progressBar" : true,
            }
            toastr.success("{{ Session::get('success') }}");
        @endif

        $(document).on("click", ".btn-edit", function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let color = $(this).data('color');

            $("#id_edit").val(id);
            $("#nama_edit").val(nama);
            $("#color_edit").val(color);
        });

        $(document).ready(function() {
            var table = $("#table").DataTable({
                destroy: true,
                scrollX: true,
                columnDefs: [{
                    orderable: false,
                    // className: 'select-checkbox',
                    targets:   0
                }],
				order: [[ 1, "asc" ]],
            });
        });
    </script>
@endsection