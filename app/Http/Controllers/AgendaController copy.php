<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\GoogleCalendar\Event as EventGoogleCalendar;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $tamu = DB::select("select * from tamu");
        $penyelenggara = DB::select("select * from penyelenggara");
        
        if ($request->ajax()) {
            // dd($request->start);
            $data = Event::whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get([
                    'id', 'title', 'description', 'start', 'end', 'color', 'penyelenggara', 'penyelenggara_unit', 'penanggung_jawab', 'contact_person', 'email_pj'
                ]);

            // $data = DB::select("select a.id, a.title, a.description, a.start, a.end, a.color, b.nama as nama_penyelenggara 
            // from events a
            // join penyelenggara b on a.penyelenggara_unit = b.id");

            return response()->json($data);
        }

        return view('agenda.index', compact('tamu', 'penyelenggara'));
    }

    public function save(Request $request)
    {
        $id_events = rand(1000, 9999);
        
        $color = [
            '#F38181',
            '#FCE38A',
            '#3F72AF',
            '#F08A5D',
            '#B83B5E',
            '#AD8B73',
        ];
        $arr = array_rand($color);

        $detail = [
            'id_events' => $id_events,
            'title' => $request->title,
            'description' => $request->deskripsi,
            'start' => date('Y-m-d H:i:s', strtotime($request->start)),
            'end' => date('Y-m-d H:i:s', strtotime($request->end)),
            'color' => $color[$arr],
            'penyelenggara' => $request->penyelenggara,
            'penyelenggara_unit' => $request->penyelenggara_1,
            'penanggung_jawab' => $request->pj,
            'contact_person' => $request->cp,
            'email_pj' => $request->email_pj,
        ];
        DB::table('events')
            ->insert($detail);

        // $this->send_notification_wa($request->cp, 'Agenda #' . $id_events . ' ' . $request->title .' berhasil di buat.');

        // insert tbl tamu
        $daftar_tamu = $request->daftar_tamu;
        $events_tamu = [];
        for ($i=0; $i < count($daftar_tamu) ; $i++) { 
            // get tamu
            $tamu = DB::table('tamu')->where('id', $daftar_tamu[$i])->first();
            
            $events_tamu[] = [
                'id_events' => $id_events,
                'id_tamu' => $daftar_tamu[$i],
                'nama_tamu' => $tamu->nama
            ];
        }
        DB::table('events_tamu')
            ->insert($events_tamu);

        // insert tbl tamu eksternal
        $nama_tamu = $request->nama_tamu;
        $email_tamu = $request->email_tamu;
        $telp_tamu = $request->telp_tamu;
        $events_tamu_eksternal = [];
        for ($i=0; $i < count($nama_tamu); $i++) { 
            $events_tamu_eksternal[] = [
                'id_events' => $id_events,
                'nama' => $nama_tamu[$i],
                'email' => $email_tamu[$i],
                'no_telp' => $telp_tamu[$i],
            ];
        }
        DB::table('events_tamu_eksternal')
            ->insert($events_tamu_eksternal);
        // dd($events_tamu_eksternal);
        // $data = [
        //     'title' => $request->title,
        //     'description' => "",
        // ];

        // Mail::send('email_agenda', $data, function($message) use ($events_tamu_eksternal) {
        //     for ($i=0; $i < count($events_tamu_eksternal) ; $i++) { 
        //         $message->to($events_tamu_eksternal[$i]['email'])
        //             ->from('demenngoding98@gmail.com', 'Demen Ngoding')
        //             ->subject('Agenda');
        //     }
        // });

        // tidak jadi insert ke google calendar karena akan double
        // $event = new EventGoogleCalendar;
        // $event->name = $request->title;
        // $event->description = $request->description;
        // $event->startDateTime = new Carbon($request->start, '+07:00');
        // $event->endDateTime = new Carbon($request->end, '+07:00');
        // $event->save();

        // send notification here
        $this->send_notification_email($request->email_pj, $events_tamu_eksternal, $detail, $id_events);
        $this->send_notification_wa($request->cp, 'Agenda #' . $id_events . ' ' . $request->title .' berhasil di buat.');

        return response()->json([
            'msg' => 'Berhasil disimpan',
            'data' => $detail
        ], 200);
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
        // id_events
        // title
        // description
        // start
        // end
        $data = [
            'id_events' => $id_events,
            'title' => $detail['title'],
            'description' => $detail['description'],
            'start' => $detail['start'],
            'end' => $detail['end'],
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

    public function send_notification_wa($target, $message)
    {
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
            'target' => $target,
            'message' => $message, 
            'countryCode' => '62', //optional
        ),
        CURLOPT_HTTPHEADER => array(
                // 'Authorization: MnxPoBHzHerBN!vxiQ!g' //V@B__rcKtsWtI1CKN0RX //change TOKEN to your actual token
                'Authorization: V@B__rcKtsWtI1CKN0RX' //V@B__rcKtsWtI1CKN0RX //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return response()->json($response, 200);
    }
}
