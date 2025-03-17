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
                SELECT column_name, data_type, is_nullable 
                FROM information_schema.columns 
                WHERE table_schema = 'public' AND table_name = ?
            ", [$table]);

            // Format hasil untuk mempermudah pembacaan
            $formattedColumns = array_map(function ($column) {
                return [
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
            // Validasi input
            $columns = $request->input('columns', []);
            if (empty($columns) || !is_array($columns)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom harus diberikan dalam bentuk array.',
                ], 400);
            }

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

            // Validasi kolom yang diminta
            $validColumns = DB::select("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_schema = 'public' AND table_name = ?
            ", [$table]);

            $validColumnNames = array_map(fn($col) => $col->column_name, $validColumns);
            $invalidColumns = array_diff($columns, $validColumnNames);

            if (!empty($invalidColumns)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom berikut tidak valid: ' . implode(', ', $invalidColumns),
                ], 400);
            }

            // Ambil data dari tabel berdasarkan kolom yang dipilih
            $data = DB::table($table)->select($columns)->get();

            return response()->json([
                'success' => true,
                'message' => "Data berhasil diambil dari tabel '{$table}'.",
                'data' => $data,
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
