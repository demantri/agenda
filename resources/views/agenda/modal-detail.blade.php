{{-- modal detail --}}
<div class="modal modal-custom" id="modal-detail" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-modal" style="font-size:large; font-weight: bold; margin: 0 auto;"></h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                    <li class="nav-item flex-fill" role="presentation">
                      <button class="nav-link w-100 active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-detail" type="button" role="tab" aria-controls="home" aria-selected="true">Detail</button>
                    </li>
                    <li class="nav-item flex-fill" role="presentation">
                      <button class="nav-link w-100" id="tamu-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-tamu" type="button" role="tab" aria-controls="tamu" aria-selected="false" tabindex="-1">Daftar Tamu</button>
                    </li>
                </ul>
                <div class="tab-content pt-3" id="borderedTabJustifiedContent">
                    <div class="tab-pane fade active show" id="bordered-justified-detail" role="tabpanel" aria-labelledby="detail-tab">
                        <div id="content-table-detail"></div>
                    </div>
                    <div class="tab-pane fade" id="bordered-justified-tamu" role="tabpanel" aria-labelledby="tamu-tab">
                        <span class="badge bg-dark">
                          Internal
                        </span>
                        <table class="table table-hover table-striped" id="table-internal">
                          <thead>
                            <tr>
                              <th style="width: 15%">#</th>
                              <th>Nama</th>
                            </tr>
                          </thead>
                          <tbody id="row-internal">

                          </tbody>
                        </table>

                        <span class="badge bg-dark">
                          Eksternal
                        </span>
                        <table class="table table-hover table-striped" id="table-eksternal">
                          <thead>
                            <tr>
                              <th style="width: 15%">#</th>
                              <th>Nama</th>
                            </tr>
                          </thead>
                          <tbody id="row-eksternal">

                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"> Tutup</button>
                @if (
                  Auth::check() == true 
                  && Auth::user()->role_name == 'admin'
                )
                <button 
                  type="button" 
                  class="btn btn-primary btn-edit-agenda"
                > Edit</button>
                @endif
                @if (
                  Auth::check() == true 
                  && Auth::user()->role_name == 'superadmin'
                )
                <button 
                  type="button" 
                  class="btn btn-outline-danger btn-hapus-agenda"
                > Hapus</button>
                @endif
            </div>
        </div>
    </div>
</div>