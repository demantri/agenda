{{-- modal detail --}}

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:large; font-weight: bold;">Edit Agenda</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form id="myformedit" method="POST">
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                          <button class="nav-link w-100 active" id="detail-tab-edit" data-bs-toggle="tab" data-bs-target="#detail-content-edit" type="button" role="tab" aria-controls="home" aria-selected="true">Detail</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                          <button class="nav-link w-100" id="daftar-tamu-tab-edit" data-bs-toggle="tab" data-bs-target="#daftar-tamu-content-edit" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Daftar Tamu</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3" id="borderedTabJustifiedContent">
                        <div class="tab-pane fade active show" id="detail-content-edit" role="tabpanel" aria-labelledby="detail-tab-edit">
                            <div class="row g-3 p-3">
                                <input type="hidden" name="id_event" id="id_event_edit">
                                <input type="hidden" name="color" id="color_edit">
                                <div class="col-md-12">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="title">Nama Agenda</label>
                                        <input type="text" class="form-control" id="title_edit" name="title" placeholder="Masukan Judul Agenda">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="allday" onclick="handleClick(this)">
                                        <label class="form-check-label" for="allday">
                                            All Day
                                        </label>
                                    </div>
                                    </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="start">Tanggal Mulai</label>
                                        <input type="datetime-local" class="form-control" id="start_edit" name="start">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="end">Tanggal Selesai</label>
                                        <input type="datetime-local" class="form-control" id="end_edit" name="end">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="penyelenggara">Penyelenggara</label>
                                        <select name="penyelenggara" id="penyelenggara_edit" class="form-control">
                                            <option value="internal">Internal</option>
                                            <option value="eksternal">Eksternal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2" id="content-penyelenggara_edit">
                                        <label class="col-form-label" for="penyelenggara_1">Penyelenggara Internal</label>
                                        <select name="penyelenggara_1" id="penyelenggara_1_edit" class="form-control">
                                            <option value="" selected disabled>- Pilih -</option>
                                            @foreach ($penyelenggara as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-line mb-2">
                                        <label for="pj" class="form-label">Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="pj_edit" name="pj" placeholder="Masukan Penanggung Jawab">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-line mb-2">
                                        <label for="cp" class="form-label">Contact Person</label>
                                        <input type="text" class="form-control numeric" id="cp_edit" name="cp" placeholder="Masukan Contact Person">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-line mb-2">
                                        <label for="email_pj" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email_pj_edit" name="email_pj" placeholder="Masukan Email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-line mb-2">
                                        <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                        <input type="number" class="form-control" id="jumlah_peserta_edit" name="jumlah_peserta" min="1" value="1">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-line mb-2">
                                        <label for="deskripsi" class="form-label">Deskripsi Agenda</label>
                                        <textarea name="deskripsi" id="deskripsi_edit" cols="10" rows="5" class="form-control" placeholder="Masukan Deskripsi Agenda" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="daftar-tamu-content-edit" role="tabpanel" aria-labelledby="daftar-tamu-tab-edit">
                            <div class="row g-3 p-3">
                                <div class="col-md-12">
                                    {{-- <div class="form-line mb-2">
                                        <label class="col-form-label" for="daftar_tamu">Tamu Internal</label>
                                        <select name="daftar_tamu[]" id="daftar_tamu" class="form-control select-dua" style="width: 100% !important" multiple data-placeholder="Pilih tamu internal">
                                            <option value=""></option>
                                            @foreach ($tamu as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="daftar_tamu">Tamu Internal</label>
                                        <input type="text" class="form-control" id="daftar_tamu_edit" name="daftar_tamu" placeholder="Input tamu internal">
                                    </div>
                                </div>
                                <div class="col-6 mt-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary" id="btn-tambah-tamu-edit">
                                        <i class="bx bx-plus"></i>
                                        Tamu Eksternal
                                    </button>
                                </div>

                                <div id="div-wrapper-tamu"></div>
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