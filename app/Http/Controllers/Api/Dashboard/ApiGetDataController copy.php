<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;

class ApiGetDataController extends Controller
{
    public function getAllTables()
    {
        try {
            // Query ke information_schema.tables untuk PostgreSQL
            $tables = DB::select("
                SELECT table_name
                FROM information_schema.tables
                WHERE table_schema = 'public'
            ");

            // Daftar tabel yang akan diabaikan
            $excludedTables = ['migrations', 'personal_access_tokens'];

            // Filter hasil query untuk mengabaikan tabel tertentu
            $tableNames = array_filter(
                array_map(fn($table) => $table->table_name, $tables),
                fn($tableName) => !in_array($tableName, $excludedTables)
            );

            return response()->json([
                'success' => true,
                'message' => 'Daftar tabel berhasil diambil.',
                'data' => array_values($tableNames), // Mengatur ulang indeks array
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil daftar tabel.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTableColumns($table)
    {
        try {
            // Periksa apakah tabel ada di database
            $tableExists = DB::select("
                SELECT table_name
                FROM information_schema.tables
                WHERE table_schema = 'public' AND table_name = ?
            ", [$table]);

            if (empty($tableExists)) {
                return response()->json([
                    'success' => false,
                    'message' => "Tabel '{$table}' tidak ditemukan di database.",
                ], 404);
            }

            // Query untuk mendapatkan semua kolom dari tabel tertentu
            $columns = DB::select("
                SELECT column_name, data_type, is_nullable, ordinal_position
                FROM information_schema.columns
                WHERE table_schema = 'public' AND table_name = ?
            ", [$table]);

            // Format hasil untuk mempermudah pembacaan
            $formattedColumns = array_map(function ($column) {
                return [
                    'id' => $column->ordinal_position,
                    'name' => $column->column_name,
                    'type' => $column->data_type,
                    'nullable' => $column->is_nullable === 'YES',
                ];
            }, $columns);

            return response()->json([
                'success' => true,
                'message' => "Daftar kolom berhasil diambil dari tabel '{$table}'.",
                'data' => $formattedColumns,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil daftar kolom.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTableDataByColumns(Request $request, $table)
    {
        try {
            // Terima input array 'dimensi' dan (opsional) string 'metriks'
            $dimensi = $request->input('dimensi', []);   // array
            $metriks = $request->input('metriks', null); // string atau null

            // Validasi dasar
            if (!is_array($dimensi) || count($dimensi) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dimensi harus dikirim sebagai array dan minimal 1.',
                ], 400);
            }

            // Pastikan tabel ada di DB
            $tableExists = DB::select("
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = 'public' AND table_name = ?
    ", [$table]);

            if (empty($tableExists)) {
                return response()->json([
                    'success' => false,
                    'message' => "Tabel '{$table}' tidak ditemukan di database.",
                ], 404);
            }

            // Mulai membangun query
            $query = DB::table($table)->select($dimensi);

            // Jika metriks diisi (tidak null/empty), lakukan COUNT DISTINCT
            if ($metriks) {
                $query->addSelect(DB::raw("COUNT(DISTINCT {$metriks}) AS total_{$metriks}"));
            }

            // Lakukan groupBy pada seluruh kolom dimensi
            $query->groupBy($dimensi);

            // Pengurutan
            if ($metriks) {
                // Urut desc berdasarkan COUNT DISTINCT metriks
                $query->orderBy(DB::raw("COUNT(DISTINCT {$metriks})"), 'desc');
            } else {
                // Jika tidak ada metriks, urutkan berdasarkan dimensi pertama
                $query->orderBy($dimensi[0], 'asc');
            }

            // Untuk debugging, bangun string query manual
            $sqlForDebug = sprintf(
                "SELECT %s, %s FROM %s GROUP BY %s ORDER BY %s DESC",
                implode(', ', $dimensi),
                $metriks ? "COUNT(DISTINCT {$metriks}) as total_{$metriks}" : "",
                $table,
                implode(', ', $dimensi),
                $metriks ? "COUNT(DISTINCT {$metriks})" : implode(', ', $dimensi)
            );

            // Eksekusi
            $data = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihitung berdasarkan dimensi dan metriks.',
                'data' => $data,
                'query' => $sqlForDebug,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
