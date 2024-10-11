<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exports\PendaftarExport;
use App\Imports\PendaftarImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiMasterPendaftar extends Controller
{
    public function index()
    {
        try {
            $pendaftar = Pendaftar::withCount('pendaftaranEvent as jumlah_pelatihan')
                ->where('is_deleted', false)
                ->orderBy('nama', 'asc')
                ->get();

            return response()->json([
                'data' => $pendaftar,
                'message' => 'Daftar pendaftar berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil daftar pendaftar',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show($idPendaftar)
    {
        try {
            $pendaftar = Pendaftar::with(['pendaftaranEvent.agendaPelatihan.pelatihan'])
                ->where('id_pendaftar', $idPendaftar)
                ->first();

            if (!$pendaftar) {
                return response()->json([
                    'message' => 'Pendaftar tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $pendaftar,
                'message' => 'Detail pendaftar berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil detail pendaftar',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($idPendaftar)
    {
        try {
            $pendaftar = Pendaftar::where('id_pendaftar', $idPendaftar)
                                ->where('is_deleted', false)
                                ->first();

            if (!$pendaftar) {
                return response()->json([
                    'message' => 'Pendaftar tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $namaPendaftar = $pendaftar->nama;

            $pendaftar->update([
                'is_deleted' => true,
                'modified_by' => 'Admin',
                'modified_time' => now(),
            ]);

            return response()->json([
                'message' => "Pendaftar $namaPendaftar berhasil dihapus (soft delete)",
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus pendaftar',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $idPendaftar)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/',
                'max:255',
                'unique:pendaftar,email,' . $idPendaftar . ',id_pendaftar'
            ],
            'no_kontak' => [
                'required',
                'string',
                'regex:/^\+?[1-9][0-9]{9,14}$/',
                'min:10',  
                'max:15'
            ],
            'aktivitas' => 'required|string|max:15',
            'nama_instansi' => 'nullable|string|max:50',
            'provinsi' => 'required|string|max:50',
            'kab_kota' => 'required|string|max:50',
            'linkedin' => 'nullable|string|max:50',
            'modified_by' => 'nullable|string|max:255'
        ], [
            'nama.required' => 'Nama lengkap harus diisi',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter',
            'email.unique' => 'Email sudah digunakan. Gunakan email yang lain',
            'email.regex' => 'Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id .',
            'no_kontak.required' => 'Kontak harus diisi',
            'no_kontak.string' => 'Kontak harus berupa teks',
            'no_kontak.min' => 'Kontak harus minimal 10 digit.',
            'no_kontak.max' => 'Kontak tidak boleh lebih dari 15 karakter.',
            'no_kontak.regex' => 'Kontak tidak boleh diawali dengan 0 dan tidak boleh memakai spesial karakter',
            'aktivitas.required' => 'Aktivitas harus diisi',
            'aktivitas.string' => 'Aktivitas harus berupa teks',
            'aktivitas.max' => 'Aktivitas tidak boleh lebih dari 15 karakter',
            'nama_instansi.string' => 'Nama instansi harus berupa teks',
            'nama_instansi.max' => 'Nama instansi tidak boleh lebih dari 50 karakter',
            'provinsi.required' => 'Provinsi harus diisi',
            'provinsi.string' => 'Provinsi harus berupa teks',
            'provinsi.max' => 'Provinsi tidak boleh lebih dari 50 karakter',
            'kab_kota.required' => 'Kabupaten/Kota harus diisi',
            'kab_kota.string' => 'Kabupaten/Kota harus berupa teks',
            'kab_kota.max' => 'Kabupaten/Kota tidak boleh lebih dari 50 karakter',
            'linkedin.string' => 'LinkedIn harus berupa teks',
            'linkedin.max' => 'LinkedIn tidak boleh lebih dari 50 karakter',
            'modified_by.string' => 'Modified by harus berupa teks',
            'modified_by.max' => 'Modified by tidak boleh lebih dari 255 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $pendaftar = Pendaftar::where('id_pendaftar', $idPendaftar)->first();

            if (!$pendaftar) {
                return response()->json([
                    'message' => 'Peserta tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $pendaftar->nama = $request->input('nama');
            $pendaftar->email = $request->input('email');
            $pendaftar->no_kontak = '+62' . ltrim($request->input('no_kontak'), '0'); 
            $pendaftar->aktivitas = $request->input('aktivitas');
            $pendaftar->nama_instansi = $request->input('nama_instansi');
            $pendaftar->provinsi = $request->input('provinsi');
            $pendaftar->kab_kota = $request->input('kab_kota');
            $pendaftar->linkedin = $request->input('linkedin');
            $pendaftar->modified_by = "Admin";
            $pendaftar->modified_time = now();
            $pendaftar->save();

            return response()->json([
                'data' => $pendaftar,
                'message' => 'Data peserta berhasil diperbarui',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data peserta gagal diperbarui',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function exportExcel(Request $request)
    {
        try {
            // Get the search parameter
            $search = $request->query('search');

            // Fetch filtered data where is_deleted is false
            $data = Pendaftar::where('is_deleted', false) // Only get records that are not deleted
                ->when($search, function ($query, $search) {
                    return $query->where('nama', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('no_kontak', 'like', "%{$search}%")
                                ->orWhere('aktivitas', 'like', "%{$search}%");
                })->get();

            return Excel::download(new PendaftarExport($data), 'Data Pendaftar.xlsx');
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengekspor data pendaftar ke Excel',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function importExcel(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:2048'
            ]);
            
            Excel::import(new PendaftarImport, $request->file('file'));

            return response()->json([
                'message' => 'Data berhasil diimpor dari Excel',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function exportCsv(): StreamedResponse
    {
        try {
            $fileName = 'pendaftar.csv';

            $pendaftar = Pendaftar::all();

            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = [
                'id_pendaftar',
                'nama',
                'email',
                'no_kontak',
                'nama_instansi',
                'provinsi',
                'kab_kota',
                'linkedin',
                'created_time'
            ];

            $callback = function () use ($pendaftar, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($pendaftar as $row) {
                    fputcsv($file, [
                        $row->id_pendaftar,
                        $row->nama,
                        $row->email,
                        $row->no_kontak,
                        $row->nama_instansi,
                        $row->provinsi,
                        $row->kab_kota,
                        $row->linkedin,
                        $row->created_time
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengekspor data pendaftar ke CSV',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function importCsv(Request $request)
    {
        try {
            $file = $request->file('file');

            // Validasi file
            $validator = Validator::make(
                ['file' => $file],
                ['file' => 'required|mimes:csv,txt|max:2048']
            );

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi file gagal',
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }

            // Baca CSV dan masukkan ke database
            $fileData = array_map('str_getcsv', file($file->getRealPath()));
            foreach ($fileData as $key => $row) {
                if ($key === 0) continue; // Skip heading

                Pendaftar::updateOrCreate(
                    ['id_pendaftar' => $row[0]],
                    [
                        'nama' => $row[1],
                        'email' => $row[2],
                        'no_kontak' => $row[3],
                        'nama_instansi' => $row[4],
                        'provinsi' => $row[5],
                        'kab_kota' => $row[6],
                        'linkedin' => $row[7],
                        'created_time' => $row[8],
                    ]
                );
            }

            return response()->json([
                'message' => 'Data berhasil diimpor dari CSV',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengimpor data dari CSV',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // public function importExcel(Request $request)
    // {
    //     try {
    //         $file = $request->file('file');

    //         // Validasi file
    //         $validator = Validator::make(
    //             ['file' => $file],
    //             ['file' => 'required|mimes:xlsx,xls|max:2048']
    //         );

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'message' => 'Validasi file gagal',
    //                 'statusCode' => Response::HTTP_BAD_REQUEST,
    //                 'status' => 'error',
    //                 'errors' => $validator->errors()
    //             ], Response::HTTP_BAD_REQUEST);
    //         }

    //         // Menggunakan Excel untuk impor data
    //         Excel::import(new PendaftarImport, $file);

    //         return response()->json([
    //             'message' => 'Data berhasil diimpor dari Excel',
    //             'statusCode' => Response::HTTP_OK,
    //             'status' => 'success'
    //         ], Response::HTTP_OK);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'message' => 'Gagal mengimpor data dari Excel',
    //             'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'status' => 'error',
    //             'error' => $e->getMessage()
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }


}
