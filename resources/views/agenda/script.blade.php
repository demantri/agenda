<script>
    @if(Session::has('success'))
        toastr.options = {
            "closeButton" : true,
            "progressBar" : true,
        }
        toastr.success("{{ Session::get('success') }}");
    @endif

    $('.numeric').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function showCalendar() {
        let site_url = '{{ url('/') }}';
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var calendar = $("#calendar").fullCalendar({
            locale: 'id',
            // aspectRatio:  2.5,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay,listMonth,listYear'
            },
            views: {
                listMonth: {
                    buttonText: 'List Bulanan'
                },
                listYear: {
                    buttonText: 'List Tahunan'
                }
            },
            defaultView: 'month',
            // googleCalendarApiKey: 'AIzaSyC9QufoUKzfLuPJdgBaGslG0G99g6QyPvQ',
            googleCalendarApiKey: 'AIzaSyCV016m3Z_Vioo1O-nQuPOCFe6HODkAoZw',
            eventSources:
            [
                {
                    googleCalendarId: 'id.indonesian#holiday@group.v.calendar.google.com'
                },
                {
                    googleCalendarId: 'dedesmantri@gmail.com',
                    // googleCalendarId: 'belozoglu.dt@gmail.com',
                    // color: '#AD1457',
                    // className: 'nice-event'
                },
            ],
            editable: false,
            events: site_url + "/agenda",
            eventRender: function(event, element) {
                let title = `<strong>${event.title}</strong>`;
                let content = `
                    <strong>Waktu</strong> : ${moment(event.start).format('DD MMMM YYYY')} - selesai
                    <strong>Penyelenggara</strong> : ${event.penyelenggara_unit}
                    <hr>
                    <strong>Deskripsi</strong> : ${event.description}
                `;
                if (window.innerWidth >= 768 ) {
                    element.popover({
                        title: title,
                        content: content,
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body',
                        html: true,
                    });
                } else {
                    $('.popover').remove();
                }
                
                return event.title.toLowerCase().indexOf($('#filter_judul').val()) >= 0
            },
            eventClick: function (event) {
                // console.log(event);
                modalDetail(event);
            },
        });
    }

    function handleClick(checkbox) {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        // today = mm + '/' + dd + '/' + yyyy;
        today = yyyy + '-' + mm + '-' + dd + 'T' + '23:59'
        
        if (checkbox.checked) {
            // true
            $("#end").prop("readonly", true);
            $("#end").val(today);
        } else {
            // false
            $("#end").prop("readonly", false);
            $("#end").val('');
            // console.log(checkbox.checked);
        }
    }

    function changeDropdownPenyelenggara() {
        $("#penyelenggara").on("change", function() {
            let value = $(this).val();
            
            let html = '';
            
            if (value == 'internal') {
                html = `<label class="col-form-label" for="penyelenggara_1">Penyelenggara Internal</label>
                                        <select name="penyelenggara_1" id="penyelenggara_1" class="form-control">
                                            <option value="" selected disabled>- Pilih -</option>
                                            @foreach ($penyelenggara as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                        `;
            } else {
                html = `
                <label for="penyelenggara_1" class="col-form-label">Penyelenggara Eksternal</label>
                <input type="text" class="form-control" name="penyelenggara_1" required>
                `;
            }

            $("#content-penyelenggara").html(html);
        });

        $("#penyelenggara_edit").on("change", function() {
            let value = $(this).val();
            
            let html = '';
            
            if (value == 'internal') {
                html = `<label class="col-form-label" for="penyelenggara_1_edit">Penyelenggara Internal</label>
                                        <select name="penyelenggara_1" id="penyelenggara_1_edit" class="form-control">
                                            <option value="" selected disabled>- Pilih -</option>
                                            @foreach ($penyelenggara as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                        `;
            } else {
                html = `
                <label for="penyelenggara_1" class="col-form-label">Penyelenggara Eksternal</label>
                <input type="text" class="form-control" name="penyelenggara_1" required>
                `;
            }

            $("#content-penyelenggara_edit").html(html);
        });
    }

    function modalAdd() {
        $("#modal-add").modal('show');

        changeDropdownPenyelenggara();

        let max = 5;
        let wrapper = $(".wrapper-tamu");
        let button = $("#btn-tambah-tamu");
        let x = 1;

        $(button).on("click", function(e) {
            e.preventDefault();
            if (x < max) {
                x++;
                let html = `<div class="wrapper-tamu mt-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-line">
                                            <input type="text" class="form-control" placeholder="Tamu - ${x}" name="nama_tamu[]" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-line">
                                            <input type="email" class="form-control" placeholder="Email - ${x}" name="email_tamu[]" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-line">
                                            <input type="number" class="form-control numeric" placeholder="Telp - ${x}" name="telp_tamu[]" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-line">
                                            <button type="button" class="btn btn-outline-danger remove_field">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $(wrapper).append(html);
            }
        });

        $(wrapper).on("click",".remove_field", function(e) {
            e.preventDefault();
            $(this).parent('div')
                .parent('div')
                .parent('div')
                .parent('div')
                .remove();
            x--;
        });
    }

    function modalDetail(event) {
        $.ajax({
            url: '{{ url('agenda/detail') }}',
            type: 'post',
            dataType: 'json',
            data: {
                id: event.id
            },
            success: function(response) {
                // console.log(response);

                let events = response.event;
                let tamu_eksternal = response.tamu_eksternal;
                let tamu_internal = response.tamu_internal;

                $("#modal-detail").modal("show");
                $("#title-modal").text(events.title);

                contentDetail(events);
                contentTamu(tamu_eksternal, tamu_internal);

                let user = '{{ Auth::user()->id ?? '' }}';
                let role = '{{ Auth::user()->role_name ?? '' }}';
                if (user == events.created_by || role == 'superadmin') {
                    $("#hapusbtn").show();
                } else {
                    $("#hapusbtn").hide();
                }

                $(".btn-hapus-agenda").on("click", function() {
                    hapusAgenda(events)
                });

                $(".btn-edit-agenda").on("click", function() {
                    editAgenda(events, tamu_eksternal, tamu_internal)
                });
            }
        });
    }

    function editAgenda(event, tamu_eksternal, tamu_internal) {
        $("#modal-detail").modal('hide');
        $("#modal-edit").modal('show');

        changeDropdownPenyelenggara();

        $("#color_edit").val(event.color);
        $("#id_event_edit").val(event.id_events);
        $("#title_edit").val(event.title);
        $("#start_edit").val(event.start);
        $("#end_edit").val(event.end);
        $("#penyelenggara_edit").val(event.penyelenggara);
        $("#penyelenggara_1_edit").val(event.unit_id);
        $("#pj_edit").val(event.penanggung_jawab);
        $("#cp_edit").val(event.contact_person);
        $("#email_pj_edit").val(event.email_pj);
        $("#jumlah_peserta_edit").val(event.jumlah_peserta);
        $("#deskripsi_edit").val(event.description);

        $("#daftar_tamu_edit").val(tamu_internal[0].nama_tamu);

        // let selectedTamu = [];
        // tamu_internal.forEach(element => {
        //     selectedTamu.push(element.id_tamu);
        //     $("#daftar_tamu_edit").val(selectedTamu).trigger('change.select2');
        // });

        let formInput = '';
        for (let i in tamu_eksternal) {
            if (i == 0) {
                formInput += `<div class="wrapper-tamu">
                                    <div class="row g-3">
                                        <input type="hidden" class="form-control" name="id_edit[]" value="${tamu_eksternal[i].id}">
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <input type="text" class="form-control nama_tamu_eksternal" name="nama_tamu[]" autocomplete="off" required value="${tamu_eksternal[i].nama}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <input type="email" class="form-control email_tamu_eksternal" name="email_tamu[]" autocomplete="off" required value="${tamu_eksternal[i].email}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-line">
                                                <input type="text" class="form-control telp_tamu_eksternal numeric" name="telp_tamu[]" autocomplete="off" required value="${tamu_eksternal[i].no_telp}">
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
            } else {
                formInput += `<div class="wrapper-tamu mt-3">
                                <div class="row g-3">
                                    <input type="hidden" class="form-control" name="id_edit[]" value="${tamu_eksternal[i].id}">
                                    <div class="col-md-4">
                                        <div class="form-line">
                                            <input type="text" class="form-control" placeholder="Tamu - ${i}" name="nama_tamu[]" autocomplete="off" value="${tamu_eksternal[i].nama}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-line">
                                            <input type="email" class="form-control" placeholder="Email - ${i}" name="email_tamu[]" autocomplete="off" value="${tamu_eksternal[i].email}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-line">
                                            <input type="number" class="form-control numeric" placeholder="Telp - ${i}" name="telp_tamu[]" autocomplete="off" value="${tamu_eksternal[i].no_telp}">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-line">
                                            <button type="button" class="btn btn-outline-danger remove_field">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
            }
        }

        $("#div-wrapper-tamu").html(formInput);

        tambahTamuEdit(tamu_eksternal.length);
    }

    function tambahTamuEdit(jml) {
        let max = 5;
        let wrapper = $("#div-wrapper-tamu");
        let button = $("#btn-tambah-tamu-edit");
        let x = jml;

        $(button).on("click", function(e) {
            e.preventDefault();
            if (x < max) {
                x++;
                let html = `<div class="wrapper-tamu mt-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Tamu - ${x}" name="nama_tamu[]" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="email" class="form-control" placeholder="Email - ${x}" name="email_tamu[]" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="number" class="form-control numeric" placeholder="Telp - ${x}" name="telp_tamu[]" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-outline-danger remove_field">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $(wrapper).append(html);
            }
        });

        $(wrapper).on("click",".remove_field", function(e) {
            e.preventDefault();
            $(this).parent('div')
                .parent('div')
                .parent('div')
                .parent('div')
                .remove();
            x--;
        });
    }

    function hapusAgenda(event) {
        let confirmTxt = confirm("Anda yakin?");
        if (confirmTxt) {
            $.ajax({
                url: '{{ url('agenda/hapus') }}',
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function() {
                    $("#loader").show()
                },
                data: event,
                success: function(response) {
                    $("#loader").hide();
                    closeModal();
                    $("#calendar").fullCalendar('removeEvents', event.id);
                    displayMessage('Agenda berhasil dihapus!');
                },
                error: function(err, status, xhr) {
                    // swal.close();
                    console.log(err);
                } 
            });
        }
    }

    function contentDetail(event) {
        let row = `
            <table class="table table-hover table-striped">
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>${moment(event.start).format('DD MMMM YYYY')} - ${moment(event.end).format('DD MMMM YYYY')}</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td>${moment(event.start).format('h:mm')} - Selesai</td>
                </tr>
                <tr>
                    <td>Penyelenggara</td>
                    <td>:</td>
                    <td>${event.penyelenggara_unit}</td>
                </tr>
                <tr>
                    <td>Penanggung Jawab</td>
                    <td>:</td>
                    <td>${event.penanggung_jawab}</td>
                </tr>
                <tr>
                    <td>Contact Person</td>
                    <td>:</td>
                    <td>${event.contact_person}</td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td>:</td>
                    <td>${event.description}</td>
                </tr>
            </table>
        `;
        $("#content-table-detail").html(row);
    }

    function contentTamu(tamu_eksternal, tamu_internal) {
        let internal = '';
        let noi = 1;
        let noe = 1;
        for (let i = 0; i < tamu_internal.length; i++) {
            if (tamu_internal.length > 0) {
                internal += `<tr>
                                <td>${noi++}</td>
                                <td>${tamu_internal[i].nama_tamu}</td>
                            </tr>`;
            } else {
                internal += `<tr>
                                <td>Tidak ada</td>
                            </tr>`;
            }
        }
        $("#row-internal").html(internal);

        let eksternal = '';
        for (let i = 0; i < tamu_eksternal.length; i++) {
            if (tamu_eksternal.length > 0) {
                eksternal += `<tr>
                                <td>${noe++}</td>
                                <td>${tamu_eksternal[i].nama}</td>
                            </tr>`;
            } else {
                eksternal += `<tr>
                                <td>Tidak ada</td>
                            </tr>`;
            }
        }
        $("#row-eksternal").html(eksternal);
    }

    function closeModal() {
        $('#modal-add').modal('hide');
        $('#modal-detail').modal('hide');
        $('#modal-edit').modal('hide');
        $("#myform")[0].reset();
        $("#myformedit")[0].reset();
    }

    function displayMessage(message) {
        toastr.success(message, 'Sukses');
    }

    function prosesSimpan() {
        let data = $("#myform").serialize();
        $.ajax({
            url: '{{ url('agenda/save') }}',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $("#loader").show();
            },
            data: data,
            success: function(response) {
                $("#loader").hide();
                swal({
                    title: "Sukses",
                    text: response.msg,
                    icon: "success"
                }).then(function() {
                    swal.close();
                    closeModal();
                    location.reload();
                });
            },
            error: function(err, status, xhr) {
                // swal.close();
                console.log(err);
            } 
        });
    }

    function prosesEdit() {
        let data = $("#myformedit").serialize();
        $.ajax({
            url: '{{ url('agenda/update') }}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            beforeSend: function() {
                $("#loader").show();
            },
            data: data,
            success: function(response) {
                $("#loader").hide();
                swal({
                    title: "Sukses",
                    text: 'Agenda berhasil diupdate',
                    icon: "success"
                }).then(function() {
                    swal.close();
                    closeModal();
                    location.reload();
                });
            },
            error: function(err, status, xhr) {
                // swal.close();
                console.log(err);
            } 
        });
    }

    function mobileCheck() {
        if (window.innerWidth >= 768 ) {
            return false;
        } else {
            $("#body").removeClass('toggle-sidebar');
            return true;
        }
    };

    $(document).ready(function() {
        mobileCheck();
        showCalendar();

        $.validator.setDefaults({
            debug: false,
            ignore: "",
            highlight: function(element) {
                $(element).closest('.form-control').addClass('is-invalid');
                $(element).siblings('.select2-container').find('.select2-selection').addClass(
                    'is-invalid');
            },
            unhighlight: function(element) {
                $(element).closest('.form-control').removeClass('is-invalid');
                $(element).siblings('.select2-container').find('.select2-selection').removeClass(
                    'is-invalid');
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select-dua') || element.hasClass('select2-without-search')) {
                    error.insertAfter(element.siblings('.select2'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
        // swal('success', 'success', 'success');
        $("#btn-add").on("click", function() {
            modalAdd();
        })

        $("#form-filter").on("submit", function(e) {
            e.preventDefault();
            $("#calendar").fullCalendar('rerenderEvents');
        });

        $("#myform").validate({
            submitHandler: function(form, e) {
                // proses simpan
                e.preventDefault();
                prosesSimpan();
            },
            rules : {
                title: {
                    required: true,
                },
                start: {
                    required: true,
                },
                end: {
                    required: true,
                },
                penyelenggara: {
                    required: true,
                },
                penyelenggara_1: {
                    required: true,
                },
                pj: {
                    required: true,
                },
                cp: {
                    required: true,
                },
                email_pj: {
                    required: true,
                },
                jumlah_peserta: {
                    required: true,
                },
                deskripsi: {
                    required: true,
                },
                daftar_tamu: {
                    required: true,
                },
                'nama_tamu[]': {
                    required: true,
                },
                'email_tamu[]': {
                    required: true,
                },
                'telp_tamu[]': {
                    required: true,
                },
            },
        });

        $("#myformedit").validate({
            submitHandler: function(form, e) {
                // proses edit
                e.preventDefault();
                prosesEdit();
            },
            rules : {
                title: {
                    required: true,
                },
                start: {
                    required: true,
                },
                end: {
                    required: true,
                },
                penyelenggara: {
                    required: true,
                },
                penyelenggara_1: {
                    required: true,
                },
                pj: {
                    required: true,
                },
                cp: {
                    required: true,
                },
                email_pj: {
                    required: true,
                },
                jumlah_peserta: {
                    required: true,
                },
                deskripsi: {
                    required: true,
                },
                daftar_tamu: {
                    required: true,
                },
                'nama_tamu[]': {
                    required: true,
                },
                'email_tamu[]': {
                    required: true,
                },
                'telp_tamu[]': {
                    required: true,
                },
            },
        });
    });
</script>