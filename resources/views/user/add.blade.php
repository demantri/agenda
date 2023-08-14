{{-- modal detail --}}

<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:large; font-weight: bold;">Tambah User</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form method="post" action="{{ url('user/store') }}">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row g-3 p-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select name="role_name" id="role_name" class="form-control" required>
                                        <option value="">- Pilih -</option>
                                        <option value="superadmin">Superadmin</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    <label for="role_name" class="form-label">Role Name</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="username" id="username" required placeholder="Masukan username">
                                    <label for="deskripsi" class="form-label">Username</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="password" class="form-control" name="password" id="password" required placeholder="Masukan password">
                                    <label for="password" class="form-label">Password</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" name="email" id="email" required placeholder="Masukan email">
                                    <label for="email" class="form-label">Email</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-fill"></i> Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="ri-check-fill"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>