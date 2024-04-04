<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Response;

class ServiceAccountController extends Controller
{
    public function fetchData(Request $request)
    {
        $tableName = $request->input('table');
        $app = $request->input('app');
        $where = $request->input('q');
        $orderBy = $request->input('order');
        $bindings = [];
        // Base query with parameter placeholders
        $query = "SELECT * FROM " . strtolower($tableName);

        // Add WHERE clause if provided
        if(isset($where)) {
            $q = explode("~", $where);
            if(sizeof($q) === 3) {
                $query .= " WHERE {$q[0]} {$q[1]} ?";
                $bindings[] = $q[2];
            }
        }

        // Add ORDER BY clause if provided
        if(isset($orderBy)) {
            $orderBy = explode("~", $orderBy);
            if(sizeof($orderBy) === 2) {
                $query .= " ORDER BY {$orderBy[0]} {$orderBy[1]}";
            }
        }

        // Get database connection based on the app
        $connection = env('DB_DATABASE_' . strtoupper($app));

        // Execute the query with parameter bindings
        try {
            $data = DB::connection($connection)
                ->select($query, $bindings);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'body' => $exception->errorInfo[0]], 200);
        }

        // Paginate the results
        $perPage = 100; // Number of items per page
        $currentPage = $request->input('page', 1); // Current page, default is 1
        $pagedData = array_slice($data, ($currentPage - 1) * $perPage, $perPage);

        // Create pagination object
        $paginatedData = new LengthAwarePaginator(
            $pagedData, count($data), $perPage, $currentPage
        );

        return response()->json(['status' => true, 'body' => $paginatedData], 200);
    }

    public function fetchTables(Request $request)
    {
        $app = $request->input('app');

        try {
            $tables = DB::connection(env('DB_DATABASE_' . strtoupper($app)))
                ->select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema='public'");
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'body' => $exception->errorInfo[0]], 200);
        }

        return Response::json(['status' => true, 'body' => $tables], 200);
    }

    public function fetchColumns(Request $request)
    {
        $tableName = $request->input('table');
        $app = $request->input('app');
        try {
            $columns = Schema::connection(env('DB_DATABASE_' . strtoupper($app)))
                ->getColumns(strtolower($tableName));
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'body' => $exception->errorInfo[0]], 200);
        }

        return Response::json(['status' => true, 'body' => $columns], 200);
    }
}
