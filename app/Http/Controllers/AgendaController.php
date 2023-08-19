<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\GoogleCalendar\Event as EventGoogleCalendar;

class AgendaController extends Controller
{
    public function index(Request $request, $filter = null)
    {
        $tamu = DB::select("select * from tamu");
        $penyelenggara = DB::select("select * from penyelenggara");
        
        if ($request->ajax()) {
            $data = Event::whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get([
                    'id', 'title', 'description', 'start', 'end', 'color', 'penyelenggara', 'penyelenggara_unit', 'penanggung_jawab', 'contact_person', 'email_pj'
                ]);

            return response()->json($data);
        }

        return view('agenda.index', compact('tamu', 'penyelenggara'));
    }

    public function save(Request $request)
    {
        $id_events = rand(1000, 9999);
        $user_id = Auth::user()->id;
        $unit = $request->penyelenggara_1;
        $get_unit = DB::select("select * from penyelenggara where id = '$unit'")[0];

        $detail = [
            'id_events' => $id_events,
            'title' => $request->title,
            'description' => $request->deskripsi,
            'jumlah_peserta' => $request->jumlah_peserta,
            'start' => date('Y-m-d H:i:s', strtotime($request->start)),
            'end' => date('Y-m-d H:i:s', strtotime($request->end)),
            'unit_id' => $unit,
            'color' => $get_unit->color,
            'penyelenggara' => $request->penyelenggara,
            'penyelenggara_unit' => $get_unit->nama,
            'penanggung_jawab' => $request->pj,
            'contact_person' => $request->cp,
            'email_pj' => $request->email_pj,
            'is_allday' => $request->is_allday == 'on' ? '1' : '0',
            'created_by' => $user_id,
        ];
        DB::table('events')
            ->insert($detail);

        // $this->send_notification_wa($request->cp, $events_tamu_eksternal, $detail);

        // insert tbl tamu
        $daftar_tamu = $request->daftar_tamu;
        $events_tamu = [
            'id_events' => $id_events,
            // 'id_tamu' => $daftar_tamu[$i],
            'nama_tamu' => $daftar_tamu
        ];
        DB::table('events_tamu')
            ->insert($events_tamu);

        // insert tbl tamu eksternal
        $nama_tamu = $request->nama_tamu;
        $email_tamu = $request->email_tamu;
        $telp_tamu = $request->telp_tamu;
        $telp_array = [];
        $events_tamu_eksternal = [];
        for ($i=0; $i < count($nama_tamu); $i++) { 
            $events_tamu_eksternal[] = [
                'id_events' => $id_events,
                'nama' => $nama_tamu[$i],
                'email' => $email_tamu[$i],
                'no_telp' => $telp_tamu[$i],
            ];

            $telp_array[] = $telp_tamu[$i];
        }
        DB::table('events_tamu_eksternal')->insert($events_tamu_eksternal);
        $this->send_notification_wa($telp_array, $events_tamu_eksternal, $detail);

        // send notification here
        $this->send_notification_email($request->email_pj, $events_tamu_eksternal, $detail, $id_events);

        $number_array = [
            $request->cp,
            '081931356522'
        ];
        $this->send_notification_wa_new($number_array, $events_tamu_eksternal, $detail);

        return response()->json([
            'msg' => 'Agenda berhasil dibuat',
            'data' => $detail
        ], 200);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $unit = $data['penyelenggara_1'];
        $get_unit = DB::select("select * from penyelenggara where id = '$unit'")[0];

        $detail = [
            // 'id_events' => $data['id_event'],
            'title' => $data['title'],
            'description' => $data['deskripsi'],
            'jumlah_peserta' => $data['jumlah_peserta'],
            'start' => date('Y-m-d H:i:s', strtotime($data['start'])),
            'end' => date('Y-m-d H:i:s', strtotime($data['end'])),
            'unit_id' => $unit,
            'color' => $get_unit->color,
            // 'color' => $color[$arr],
            'penyelenggara' => $data['penyelenggara'],
            'penyelenggara_unit' => $get_unit->nama,
            'penanggung_jawab' => $data['pj'],
            'contact_person' => $data['cp'],
            'email_pj' => $data['email_pj'],
        ];
        DB::table('events')
            ->where('id_events', $data['id_event'])
            ->update($detail);

        // delete user nya
        // DB::table('events_tamu')->where('id_events', $data['id_event'])->delete();

        // insert
        $daftar_tamu = $data['daftar_tamu'];
        // $events_tamu = [];
        // for ($i=0; $i < count($daftar_tamu) ; $i++) { 
        //     // get tamu
        //     $tamu = DB::table('tamu')->where('id', $daftar_tamu[$i])->first();
            
        //     $events_tamu[] = [
        //         'id_events' => $data['id_event'],
        //         'id_tamu' => $daftar_tamu[$i],
        //         'nama_tamu' => $tamu->nama
        //     ];
        // }
        $events_tamu = [
            'nama_tamu' => $daftar_tamu
        ];
        DB::table('events_tamu')
            ->where('id_events', $data['id_event'])
            ->update($events_tamu);


        // delete events_tamu_eksternal
        DB::table('events_tamu_eksternal')->where('id_events', $data['id_event'])->delete();

        // update tamu eksternal multiple
        $id_tamu = $data['id_edit'];
        $nama_tamu = $data['nama_tamu'];
        $email_tamu = $data['email_tamu'];
        $telp_tamu = $data['telp_tamu'];
        $events_tamu_eksternal = [];
        for ($i=0; $i < count($nama_tamu); $i++) { 
            $events_tamu_eksternal[] = [
                'id_events' => $data['id_event'],
                'nama' => $nama_tamu[$i],
                'email' => $email_tamu[$i],
                'no_telp' => $telp_tamu[$i],
            ];
        }
        DB::table('events_tamu_eksternal')
            ->insert($events_tamu_eksternal);
            
        $result = [
            'id_events' => $data['id_event'],
            'title' => $data['title'],
            'description' => $data['deskripsi'],
            'start' => $data['start'],
            'end' => $data['end'],
            // 'color' => $data['color'],
        ];

        return response()->json($result, 200);

        // return response()->json([
        //     'msg' => 'Agenda berhasil diupdate.',
        //     'data' => $result
        // ], 200);
    }

    public function filter(Request $request)
    {
        $filter = $request->filter_agenda;
        
        $data = Event::where('title', 'like', '%' . $filter . '%')
            ->get([
                'id', 'title', 'description', 'start', 'end', 'color'
            ]);

        return response()->json($data);
    }

    public function detail(Request $request) {
        $id = $request->id;
        $event = DB::table('events')->where('id', $id)->first();
        $tamu_internal = DB::table('events_tamu')->where('id_events', $event->id_events)->get();
        $tamu_eksternal = DB::table('events_tamu_eksternal')->where('id_events', $event->id_events)->get();

        $data = [
            'event' => $event,
            'tamu_internal' => $tamu_internal,
            'tamu_eksternal' => $tamu_eksternal
        ];

        return response()->json($data);
    }

    // tidak jadi dipakai untuk integrasi ke google calendar
    public function google_calendar()
    {
        $event = new EventGoogleCalendar;
        $event->name = 'Event untuk testing aplikasi';
        $event->description = 'Event description';
        $event->startDateTime = Carbon::now();
        $event->endDateTime = Carbon::now()->addHour();
        // $event->addAttendee([
        //     'email' => 'john@example.com',
        //     'name' => 'John Doe',
        //     'comment' => 'Lorum ipsum',
        // ]);
        $event->addAttendee(['email' => 'demenngoding98@gmail.com']);
        // $event->addMeetLink(); // optionally add a google meet link to the event

        $event->save();
    }

    public function send_notification_email($email_pj, $data_tamu_external, $detail, $id_events)
    {
        $sender = 'demenngoding98@gmail.com';
        // send email for penanggung jawab
        $data = [
            'id_events' => $id_events,
            'title' => $detail['title'],
            'description' => $detail['description'],
            'start' => $detail['start'],
            'end' => $detail['end'],
            'penanggung_jawab' => $detail['penanggung_jawab'],
            'contact_person' => $detail['contact_person'],
            'email_pj' => $detail['email_pj'],
            'tamu' => $data_tamu_external
        ];

        Mail::send('email_agenda', $data, function($message) use ($email_pj, $sender) {
            $message->to($email_pj)
                ->from($sender, 'Demen Ngoding')
                ->subject('Agenda Baru');
        });

        // send email for tamu eksternal
        Mail::send('email_agenda', $data, function($message) use ($data_tamu_external, $sender) {
            for ($i=0; $i < count($data_tamu_external) ; $i++) { 
                $message->to($data_tamu_external[$i]['email'])
                    ->from($sender, 'Demen Ngoding')
                    ->subject('Agenda Baru');
            }
        });

    }

    public function send_notification_wa_new($target, $events_tamu_eksternal, $detail)
    {
        $send_number_fix = implode(',', $target);
        $data = [
            'id_events' => $detail['id_events'],
            'title' => $detail['title'],
            'description' => $detail['description'],
            'start' => $detail['start'],
            'end' => $detail['end'],
            'penanggung_jawab' => $detail['penanggung_jawab'],
            'contact_person' => $detail['contact_person'],
            'email_pj' => $detail['email_pj'],
            'tamu' => $events_tamu_eksternal
        ];

        $no = 1;
        $message = "*Notifikasi Agenda #".$data['id_events']."*\n\nJudul Agenda : ".$data['title']."\nWaktu : ". date('d-m-Y H:i:s', strtotime($data['start'])) ." (WIB)\n\n_Bagian internal_\nNama Penanggung Jawab Kegiatan\n- Nama : ". $data['penanggung_jawab'] ."\nKontak Person Penanggung Jawab Kegiatan :\n- Whatsapp :  ". $data['contact_person'] ."\n- Email : ".$data['email_pj']."\n\nBerikut ini merupakan tamu yang di udang :\n";

        foreach ($data['tamu'] as $item) {
            $message .= $no++ . ". " . $item['nama'] . " - " . $item['no_telp'] . " - ". $item['email'];
        }

        $message .= "\n\n" . $data['description'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $send_number_fix,
            'message' => $message, 
            'countryCode' => '62', //optional
        ),
        CURLOPT_HTTPHEADER => array(
            
                'Authorization: MnxPoBHzHerBN!vxiQ!g' //V@B__rcKtsWtI1CKN0RX //change TOKEN to your actual token
                // 'Authorization: V@B__rcKtsWtI1CKN0RX' //V@B__rcKtsWtI1CKN0RX //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return response()->json($response, 200);
    }

    public function send_notification_wa($target, $events_tamu_eksternal, $detail)
    {
        $number_target = implode(',', $target);
        
        $data = [
            'id_events' => $detail['id_events'],
            'title' => $detail['title'],
            'description' => $detail['description'],
            'start' => $detail['start'],
            'end' => $detail['end'],
            'penanggung_jawab' => $detail['penanggung_jawab'],
            'contact_person' => $detail['contact_person'],
            'email_pj' => $detail['email_pj'],
            'tamu' => $events_tamu_eksternal
        ];

        $no = 1;
        $message = "*Notifikasi Agenda #".$data['id_events']."*\n\nJudul Agenda : ".$data['title']."\nWaktu : ". date('d-m-Y H:i:s', strtotime($data['start'])) ." (WIB)\n\n_Bagian internal_\nNama Penanggung Jawab Kegiatan\n- Nama : ". $data['penanggung_jawab'] ."\nKontak Person Penanggung Jawab Kegiatan :\n- Whatsapp :  ". $data['contact_person'] ."\n- Email : ".$data['email_pj']."\n\nBerikut ini merupakan tamu yang di udang :\n";

        foreach ($data['tamu'] as $item) {
            $message .= $no++ . ". " . $item['nama'] . " - " . $item['no_telp'] . " - ". $item['email'];
        }

        $message .= "\n\n" . $data['description'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $number_target,
            'message' => $message, 
            'countryCode' => '62', //optional
        ),
        CURLOPT_HTTPHEADER => array(
            
                'Authorization: MnxPoBHzHerBN!vxiQ!g' //V@B__rcKtsWtI1CKN0RX //change TOKEN to your actual token
                // 'Authorization: V@B__rcKtsWtI1CKN0RX' //V@B__rcKtsWtI1CKN0RX //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return response()->json($response, 200);
    }

    public function hapus(Request $request)
    {
        $event = $request->all();

        DB::table('events')
            ->where('id_events', $event['id_events'])
            ->delete();

        DB::table('events_tamu')
            ->where('id_events', $event['id_events'])
            ->delete();

        DB::table('events_tamu_eksternal')
            ->where('id_events', $event['id_events'])
            ->delete();

        return response()->json($event, 200);
    }
}
