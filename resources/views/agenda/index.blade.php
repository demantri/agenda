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
                                @if (Auth::check() == true)
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
@endsection
@section('script')
    <script>
        let site_url = '{{ url('/') }}';
            
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.numeric').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        function handleClick(checkbox) {
            if (checkbox.checked) {
                // true
                $("#end").prop("readonly", true);
            } else {
                // false
                $("#end").prop("readonly", false);
                console.log(checkbox.checked);
            }
        }

        function changeDropdownPenyelenggara() {
            $("#penyelenggara").on("change", function() {
                let value = $(this).val();
                
                let html = '';
                
                if (value == 'internal') {
                    html = `<select name="penyelenggara_1" id="penyelenggara_1" class="form-control">
                                <option value="">- Pilih -</option>
                                @foreach ($penyelenggara as $item)
                                <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <label for="penyelenggara_1" class="form-label">Penyelenggara Internal</label>
                            `;
                } else {
                    html = `<input type="text" class="form-control" name="penyelenggara_1">
                    <label for="penyelenggara_1" class="form-label">Penyelenggara Eksternal</label>
                    `;
                }
    
                $("#content-penyelenggara").html(html);
            });
        }

        function modalAdd() {
            $("#modal-add").modal('show');

            changeDropdownPenyelenggara();

            $("#daftar_tamu").select2({
                placeholder: "Harap pilih tamu",
                dropdownParent: $("#modal-add .modal-content")
            });

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

        function modalDetail(event) {
            $.ajax({
                url: '{{ url('agenda/detail') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: event.id
                },
                success: function(response) {
                    console.log(response);

                    let events = response.event;
                    let tamu_eksternal = response.tamu_eksternal;
                    let tamu_internal = response.tamu_internal;

                    $("#modal-detail").modal("show");
                    $("#title-modal").text(events.title);

                    contentDetail(events);
                    contentTamu(tamu_eksternal, tamu_internal);
                }
            });
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
            $("#myform")[0].reset();
        }

        function displayMessage(message) {
            toastr.success(message, 'Event');
        } 

        $(document).ready(function() {

            $("#btn-add").on("click", function() {
                modalAdd();
            })

            $("#form-filter").on("submit", function(e) {
                e.preventDefault();
                $("#calendar").fullCalendar('rerenderEvents');
            });

            $("#myform").on("submit", function(e) {
                e.preventDefault();
                let data = $("#myform").serialize();
                $.ajax({
                    url: '{{ url('agenda/save') }}',
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: data,
                    success: function(response) {
                        closeModal();

                        displayMessage(response.msg);

                        $("#calendar").fullCalendar('renderEvent', {
                            id_events: response.data.id_events,
                            title: response.data.title,
                            description: response.data.description,
                            start: response.data.start,
                            end: response.data.end,
                            color: response.data.color
                        }, true);

                        $("#calendar").fullCalendar('unselect');
                    },
                    error: function(err, status, xhr) {
                        // swal.close();
                        console.log(err);
                    } 
                });
            })

            $("#calendar").fullCalendar({
                locale: 'id',
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
                eventRender: function(event, element, view) {
                    return ['', event.title].indexOf($('#filter_judul').val()) >= 0
                },
                eventClick: function (event) {
                    console.log(event);
                    modalDetail(event);
                }

                // events:
                // [
                //     {
                //         title: 'Meeting 1',
                //         description: 'Meeting pembuatan aplikasi 1',
                //         start: '2023-07-01 08:00:00',
                //         end: '2023-07-01 12:00:00',
                //     },
                //     {
                //         title: 'Meeting 2',
                //         description: 'Meeting pembuatan aplikasi 2',
                //         start: '2023-07-02 08:00:00',
                //         end: '2023-07-02 12:00:00',
                //     },
                //     {
                //         title: 'Meeting 3',
                //         description: 'Meeting pembuatan aplikasi 3',
                //         start: '2023-07-03 08:00:00',
                //         end: '2023-07-03 12:00:00',
                //     },
                //     {
                //         title: 'Testing aplikasi',
                //         description: 'Testing aplikasi yang sudah golive',
                //         start: '2023-07-03 08:00:00',
                //         end: '2023-07-03 12:00:00',
                //     },
                // ],
            });
        });
    </script>
@endsection