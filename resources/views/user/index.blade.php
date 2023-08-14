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
                    <table class="table" id="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Username</th>
                                <th scope="col">Role</th>
                                <th scope="col">Email</th>
                                <th scope="col">Password</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data as $item)
                            <tr>
                                <td scope="row">{{ $no++ }}</td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->role_name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->password }}</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-secondary btn-edit"
                                        data-bs-target="#edit"
                                        data-bs-toggle="modal"
                                        data-id="{{ $item->id }}"
                                        data-username="{{ $item->username }}"
                                        data-role_name="{{ $item->role_name }}"
                                        data-email="{{ $item->email }}"
                                        data-password="{{ $item->password }}"
                                    >Edit</button>
                                    @if ($item->role_name != 'superadmin')
                                    <a href="{{ url('user/delete/' . $item->id ) }}" class="btn btn-sm btn-outline-danger">Hapus</a>
                                    @endif
                                    {{-- <a href="{{ url('user/delete/' . $item->id ) }}" class="btn btn-sm btn-outline-success">Reset Password</a> --}}
                                </td>
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

        $(document).ready(function() {
            var table = $("#table").DataTable({
                scrollX: true
            });

            $(".btn-edit").on("click", function() {
                let id = $(this).data('id');
                let username = $(this).data('username');
                let role_name = $(this).data('role_name');
                let email = $(this).data('email');
                let password = $(this).data('password');

                $("#id_edit").val(id);
                $("#role_name_edit").val(role_name);
                $("#username_edit").val(username);
                $("#password_edit").val(password);
                $("#email_edit").val(email);
            });
        });
    </script>
@endsection