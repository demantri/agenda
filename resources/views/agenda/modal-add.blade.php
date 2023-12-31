{{-- modal add --}}
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:large; font-weight: bold;">Tambah Agenda</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form id="myform" method="POST">
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                          <button class="nav-link w-100 active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail-content" type="button" role="tab" aria-controls="home" aria-selected="true">Detail</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                          <button class="nav-link w-100" id="daftar-tamu-tab" data-bs-toggle="tab" data-bs-target="#daftar-tamu-content" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Daftar Tamu</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3" id="borderedTabJustifiedContent">
                        <div class="tab-pane fade active show" id="detail-content" role="tabpanel" aria-labelledby="detail-tab">
                            <div class="row g-3 p-3">
                                <div class="col-md-12">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="title">Nama Agenda</label>
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Masukan Judul Agenda">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="allday" name="allday" onclick="handleClick(this)">
                                        <label class="form-check-label" for="allday">
                                            All Day
                                        </label>
                                    </div>
                                    </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="start">Tanggal Mulai</label>
                                        <input type="datetime-local" class="form-control" id="start" name="start">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="end">Tanggal Selesai</label>
                                        <input type="datetime-local" class="form-control" id="end" name="end">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2">
                                        <label class="col-form-label" for="penyelenggara">Penyelenggara</label>
                                        <select name="penyelenggara" id="penyelenggara" class="form-control">
                                            <option value="internal">Internal</option>
                                            <option value="eksternal">Eksternal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-line mb-2" id="content-penyelenggara">
                                        <label class="col-form-label" for="penyelenggara_1">Penyelenggara Internal</label>
                                        <select name="penyelenggara_1" id="penyelenggara_1" class="form-control">
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
                                        <input type="text" class="form-control" id="pj" name="pj" placeholder="Masukan Penanggung Jawab">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-line mb-2">
                                        <label for="cp" class="form-label">Contact Person</label>
                                        <input type="text" class="form-control numeric" id="cp" name="cp" placeholder="Masukan Contact Person">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-line mb-2">
                                        <label for="email_pj" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email_pj" name="email_pj" placeholder="Masukan Email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-line mb-2">
                                        <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                        <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="1">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-line mb-2">
                                        <label for="deskripsi" class="form-label">Deskripsi Agenda</label>
                                        <textarea name="deskripsi" id="deskripsi" cols="10" rows="5" class="form-control" placeholder="Masukan Deskripsi Agenda" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="daftar-tamu-content" role="tabpanel" aria-labelledby="daftar-tamu-tab">
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
                                        <input type="text" class="form-control" id="daftar_tamu" name="daftar_tamu" placeholder="Input tamu internal">
                                    </div>
                                </div>
                                <div class="col-6 mt-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary" id="btn-tambah-tamu">
                                        <i class="bx bx-plus"></i>
                                        Tamu Eksternal
                                    </button>
                                </div>
                                <div class="wrapper-tamu">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <input type="text" class="form-control" placeholder="Tamu - 1" name="nama_tamu[]" autocomplete="off">
                                            </div>
                                        </div>
    
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <input type="email" class="form-control" placeholder="Email - 1" name="email_tamu[]" autocomplete="off">
                                            </div>
                                        </div>
    
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <input type="text" class="form-control numeric" placeholder="Telp - 1" name="telp_tamu[]" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-fill"></i> Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>