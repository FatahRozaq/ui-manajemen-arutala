<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;

class ApiKelolaDashboardController extends Controller
{
    // public function convertSql(Request $request)
    // {
    //     $sql = trim($request->input('sql'));

    //     if (!$sql) {
    //         return response()->json(['error' => 'SQL query is required'], 400);
    //     }

    //     try {
    //         // Parsing nama tabel dari SQL (ambil kata setelah "FROM")
    //         preg_match('/FROM\s+(\w+)/i', $sql, $matches);
    //         if (!isset($matches[1])) {
    //             return response()->json(['error' => 'Invalid SQL syntax: No table found'], 400);
    //         }

    //         $table = $matches[1];

    //         // Pastikan tabel ada di database
    //         if (!DB::getSchemaBuilder()->hasTable($table)) {
    //             return response()->json(['error' => "Table '$table' does not exist"], 400);
    //         }

    //         // Parsing SELECT fields (ambil bagian setelah "SELECT" dan sebelum "FROM")
    //         preg_match('/SELECT\s+(.*?)\s+FROM/i', $sql, $fieldsMatch);
    //         $fields = isset($fieldsMatch[1]) ? explode(',', $fieldsMatch[1]) : ['*'];

    //         // Parsing kondisi WHERE (jika ada)
    //         $whereClause = [];
    //         if (preg_match('/WHERE\s+(.+)/i', $sql, $whereMatch)) {
    //             $conditions = explode('AND', $whereMatch[1]);
    //             foreach ($conditions as $condition) {
    //                 preg_match('/(\w+)\s*=\s*[\'"]?([^\'"]+)[\'"]?/i', trim($condition), $conditionMatch);
    //                 if (isset($conditionMatch[1]) && isset($conditionMatch[2])) {
    //                     $whereClause[$conditionMatch[1]] = $conditionMatch[2];
    //                 }
    //             }
    //         }

    //         // Parsing GROUP BY (jika ada)
    //         $groupByField = null;
    //         if (preg_match('/GROUP BY\s+(\w+)/i', $sql, $groupByMatch)) {
    //             $groupByField = $groupByMatch[1];
    //         }

    //         // Bangun Query Eloquent
    //         $query = DB::table($table)->selectRaw(implode(',', $fields));

    //         // Tambahkan kondisi WHERE jika ada
    //         if (!empty($whereClause)) {
    //             foreach ($whereClause as $column => $value) {
    //                 $query->where($column, $value);
    //             }
    //         }

    //         // Tambahkan GROUP BY jika ada
    //         if ($groupByField) {
    //             $query->groupBy($groupByField);
    //             $query->selectRaw("COUNT(*) as total");
    //         }

    //         // Eksekusi Query
    //         $result = $query->get();

    //         // Format data untuk ApexCharts jika menggunakan GROUP BY
    //         if ($groupByField) {
    //             $chartData = [
    //                 'categories' => $result->pluck($groupByField),
    //                 'series' => [
    //                     [
    //                         'name' => 'Total',
    //                         'data' => $result->pluck('total')
    //                     ]
    //                 ]
    //             ];
    //         } else {
    //             $chartData = ['data' => $result];
    //         }

    //         return response()->json($chartData, 200);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Invalid SQL or unsupported conversion: ' . $e->getMessage()], 400);
    //     }
    // }

    public function convertSql(Request $request)
    {
        $sql = $request->input('sql');

        try {
            // Menjalankan query secara dinamis
            $data = DB::select($sql);

            if (empty($data)) {
                return response()->json(['error' => 'Tidak ada data ditemukan.'], 400);
            }

            // Konversi data ke array
            $categories = [];
            $seriesData = [];

            foreach ($data as $row) {
                $row = (array) $row;
                $keys = array_keys($row);

                // Ambil kolom pertama sebagai kategori (x-axis), ubah NULL menjadi "Tanpa Keterangan"
                $category = $row[$keys[0]] ?? 'Tanpa Keterangan';
                if (is_null($category) || $category === '') {
                    $category = 'Tanpa Keterangan';
                }
                if (!in_array($category, $categories)) {
                    $categories[] = $category;
                }

                // Ambil kolom kedua sebagai nilai (y-axis), ubah NULL menjadi 0
                $value = $row[$keys[1]] ?? 0;
                if (is_null($value) || $value === '') {
                    $value = 0;
                }

                $seriesData[] = $value;
            }

            return response()->json([
                'categories' => $categories,
                'series' => [[
                    'name' => 'Total',
                    'data' => $seriesData
                ]]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Query tidak valid: ' . $e->getMessage()], 400);
        }
    }
}
