<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Response;

class ServiceAccountController extends Controller
{
    public function fetchData(Request $request)
    {
        $modelName = $request->input('model');
        $app = $request->input('app');
        $query = $request->input('q');

        $modelClass = "Modules\\" . $app . "\\Entities\\" . $modelName;
        if (class_exists($modelClass)) {
            $find = $modelClass::orderBy('id');
            if(isset($query)) {
                $q = explode("~", $query);
                if(sizeof($q) === 3)
                    $find = $find->where($q[0], $q[1], $q[2]);
            }

            $find = $find->paginate(100)->onEachSide(1)->appends(request()->query());
            return Response::json(['status' => true, 'body' => $find], 200);
        }

        return Response::json(['status' => false, 'body' => 'no results'], 401);
    }

    public function fetchModels(Request $request)
    {
        $app = $request->input('app');
        $modelFiles = File::allFiles(app_path("../Modules/" . $app . "/Entities"));
        $models = [];

        foreach ($modelFiles as $modelFile) {
            $file = explode("../Modules/" . $app . "/Entities/", $modelFile);
            $class = substr($file[1], 0, strrpos($file[1], '.'));
            $models[] = $class;
        }

        return Response::json(['status' => true, 'body' => $models], 200);
    }

    public function fetchColumns(Request $request)
    {
        $modelName = $request->input('model');
        $app = $request->input('app');
        $modelClass = "Modules\\" . $app . "\\Entities\\" . $modelName;
        $columns = Schema::connection(env('DB_DATABASE_' . strtoupper($app)))
            ->getColumns((new $modelClass)->getTable());

        return Response::json(['status' => true, 'body' => $columns], 200);
    }
}
