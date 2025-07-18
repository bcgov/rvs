<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ServiceAccountController extends Controller
{
    public function fetchData(Request $request): JsonResponse {
        $tableName = $request->input('table', '');
        $app = $request->input('app');
        $where = $request->input('q');
        $orderBy = $request->input('order');
        $perPage = $request->input('per_page', 5000);
        $connection = env('DB_DATABASE_' . strtoupper((string) $app));

        // Base query with parameter placeholders
        $query = DB::connection($connection)->table(strtolower((string) $tableName));

        // Add WHERE clause if provided
        if(isset($where)) {
            foreach ($where as $condition) {
                // Extract the column name, operator, and value from the condition
                $columnName = $condition['column'];
                $operator = $condition['operator'];
                $value = $condition['value'];

                // Add the where condition to the query
                $query->where($columnName, $operator, $value);
            }
        }

        // Add ORDER BY clause if provided
        if(isset($orderBy)) {
            $orderBy = explode("~", (string) $orderBy);
            if(sizeof($orderBy) === 2) {
                $query->orderBy($orderBy[0], $orderBy[1]);
            }
        }

        // pagination parameters
        $currentPage = $request->input('page', 1); // Current page, default is 1
        $offset = ($currentPage - 1) * $perPage;

        // append LIMIT and OFFSET to the query
        $query->limit($perPage)->offset($offset);

        // Execute the query with parameter bindings
        try {
            $data = $query->get();
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'body' => $exception->getMessage()]);
        }

        // Fetch total count for pagination
        $totalCountQuery = "SELECT COUNT(*) AS total FROM " . strtolower((string) $tableName);
        $totalCount = DB::connection($connection)->selectOne($totalCountQuery);

        // Create pagination object
        $paginatedData = new LengthAwarePaginator(
            $data, $totalCount->total, $perPage, $currentPage
        );


        return response()->json(['status' => true, 'body' => $paginatedData]);
    }

    public function fetchTables(Request $request): JsonResponse {
        $app = $request->input('app');

        try {
            $tables = DB::connection(env('DB_DATABASE_' . strtoupper((string) $app)))
                ->select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema='public'");
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'body' => $exception->getMessage()]);
        }

        return Response::json(['status' => true, 'body' => $tables], 200);
    }

    public function fetchColumns(Request $request): JsonResponse {
        $tableName = $request->input('table');
        $app = $request->input('app');
        try {
            $columns = Schema::connection(env('DB_DATABASE_' . strtoupper((string) $app)))
                ->getColumns(strtolower((string) $tableName));
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'body' => $exception->getMessage()]);
        }

        return Response::json(['status' => true, 'body' => $columns], 200);
    }
}
