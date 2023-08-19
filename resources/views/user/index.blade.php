@extends('layouts.app')

@section('title')
    Manajemen User
@endsection

@section('breadcrumb')
<div class="pagetitle">
    <h1>Manajemen User</h1>
    <nav>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">User</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
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
                    
                    <button class="btn btn-primary mt-3 mb-3"
                    data-bs-target="#add"
                    data-bs-toggle="modal"
                    >
                        <i class="bi bi-plus"></i>
                        Tambah
                    </button>
                    <table class="table table-hover" id="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Action</th>
                                <th class="text-nowrap">No</th>
                                <th class="text-nowrap">Nama Petugas</th>
                                <th class="text-nowrap">Unit/bagian</th>
                                <th class="text-nowrap">Username</th>
                                <th class="text-nowrap">Role</th>
                                <th class="text-nowrap">Email</th>
                                <th class="text-nowrap">Password</th>
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
                                            data-username="{{ $item->username }}"
                                            data-role_name="{{ $item->role_name }}"
                                            data-email="{{ $item->email }}"
                                            data-password="{{ $item->password }}"
                                            data-nama_petugas="{{ $item->nama_petugas }}"
                                            data-unit_id="{{ $item->unit_id }}"
                                        ><i class="ri-pencil-fill"></i></button>
                                        @if ($item->role_name != 'superadmin')
                                        <a href="{{ url('user/delete/' . $item->id ) }}" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-5-line"></i></a>
                                        @endif
                                    </div>
                                    {{-- <a href="{{ url('user/delete/' . $item->id ) }}" class="btn btn-sm btn-outline-success">Reset Password</a> --}}
                                </td>
                                <td scope="row">{{ $no++ }}</td>
                                <td>{{ $item->nama_petugas }}</td>
                                <td>{{ $item->unit_name }}</td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->role_name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->password }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@include('user.add')
@include('user.edit')

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
            let username = $(this).data('username');
            let role_name = $(this).data('role_name');
            let email = $(this).data('email');
            let password = $(this).data('password');
            let nama_petugas = $(this).data('nama_petugas');
            let unit_id = $(this).data('unit_id');

            $("#id_edit").val(id);
            $("#role_name_edit").val(role_name);
            $("#username_edit").val(username);
            $("#password_edit").val(password);
            $("#email_edit").val(email);
            $("#nama_petugas_edit").val(nama_petugas);
            $("#unit_edit").val(unit_id);
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

            // $(".btn-edit").on("click", function() {
            // let id = $(this).data('id');
            // let username = $(this).data('username');
            // let role_name = $(this).data('role_name');
            // let email = $(this).data('email');
            // let password = $(this).data('password');

            // $("#id_edit").val(id);
            // $("#role_name_edit").val(role_name);
            // $("#username_edit").val(username);
            // $("#password_edit").val(password);
            // $("#email_edit").val(email);
            // });
        });
    </script>
@endsection