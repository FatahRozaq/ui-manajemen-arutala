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
        try {
            $pendaftar = Pendaftar::find($idPendaftar);

            if (!$pendaftar) {
                return response()->json([
                    'message' => 'Pendaftar tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $pendaftar->update($request->all());

            return response()->json([
                'data' => $pendaftar,
                'message' => 'Pendaftar berhasil diperbarui',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui pendaftar',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function exportExcel()
    {
        try {
            return Excel::download(new PendaftarExport, 'pendaftar.xlsx');
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
