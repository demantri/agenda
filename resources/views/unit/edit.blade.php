{{-- modal detail --}}

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:large; font-weight: bold;">Edit Unit</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form method="post" action="{{ url('unit/update') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="id_edit" name="id">
                            <div class="form-line mb-2">
                                <label class="col-form-label" for="nama">Nama unit/bagian</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama" placeholder="Input unit/bagian" required>
                            </div>
                            <div class="form-line mb-2">
                                <label class="col-form-label" for="color">Warna Agenda</label>
                                <input type="color" class="form-control form-control-color" id="color_edit" name="color" placeholder="Input warna agenda" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-fill"></i> Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>