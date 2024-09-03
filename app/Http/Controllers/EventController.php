<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    public function index()
    {
        // $json = File::get(public_path('asset/daftar_event.json'));
        // $events = json_decode($json, true)['data'];

        // $months = [
        //     'Januari' => 'January',
        //     'Februari' => 'February',
        //     'Maret' => 'March',
        //     'April' => 'April',
        //     'Mei' => 'May',
        //     'Juni' => 'June',
        //     'Juli' => 'July',
        //     'Agustus' => 'August',
        //     'September' => 'September',
        //     'Oktober' => 'October',
        //     'November' => 'November',
        //     'Desember' => 'December'
        // ];

        // foreach ($events as &$event) {
        //     foreach ($months as $id => $en) {
        //         $event['startDate'] = str_replace($id, $en, $event['startDate']);
        //     }
        // }

        return view('user/daftarEvent/DaftarEvent');
    }

    public function showEvent()
    {
        // $json = File::get(public_path('asset/daftar_event.json'));
        // $events = json_decode($json, true)['data']; // Mengambil data array

        // // Mencari event dengan ID yang sesuai
        // $event = collect($events)->firstWhere('id', $id);

        // if (!$event) {
        //     abort(404, 'Event not found'); // Menampilkan halaman 404 jika event tidak ditemukan
        // }

        // return view('user/daftarEvent/DetailEvent', compact('event'));
        return view('user/daftarEvent/DetailEvent');
    }

    // public function showDetail($id)
    // {
    //     // Fetch data from API menggunakan helper route
    //     $response = Http::get(route('laman-peserta.event-detail', ['id' => $id]));

    //     if ($response->failed()) {
    //         abort(404, 'Event not found');
    //     }

    //     $data = $response->json()['data'];

    //     // Return view dengan data event
    //     return view('DetailEvent', ['event' => $data]);
    // }


    public function myEvent()
    {
        $json = File::get(public_path('asset/daftar_event.json'));
        $events = json_decode($json, true)['data']; // Mengambil data array

        // Define the desired order of statuses
        $statusOrder = [
            'Sedang Berlangsung' => 1,
            'Masa Pendaftaran' => 2,
            'Selesai' => 3
        ];

        // Sort the events based on the status order
        usort($events, function ($a, $b) use ($statusOrder) {
            $statusA = $statusOrder[$a['status']] ?? 4; // Default to 4 if status not found
            $statusB = $statusOrder[$b['status']] ?? 4;
            return $statusA <=> $statusB;
        });

        // Pass the sorted events data to the view
        return view('user/myEvent/MyEvent', compact('events'));
    }
}
