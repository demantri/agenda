<p>
    <strong>Notifikasi Agenda #{{ $id_events }}</strong><br><br>
    Judul Agenda : {{ $title }} <br>
    Waktu : {{ date('d-m-Y', strtotime($start)) }} WIB <br><br>

    <i>Bagian Internal</i> <br>
    Nama Penanggung Jawab Kegiatan : <br>
    - Nama : {{ $penanggung_jawab }} <br>
    Kontak Person Penanggung Jawab Kegiatan : <br>
    - Whatsapp :  {{ $contact_person }} <br>
    - Email : {{ $email_pj }} <br><br>

    Berikut ini merupakan tamu yang di udang : <br>
    @php
        $no = 1;
    @endphp
    @foreach ($tamu as $item)
        {{ $no++ . '. ' . $item['nama'] .' - '. $item['no_telp'] .' - '. $item['email'] }} <br><br>
    @endforeach

    {{ $description }}
</p>