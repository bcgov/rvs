<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
class ServiceAccountController extends Controller
{
    public function fetch(Request $request)
    {
        $modelName = $request->input('model');
        $app = $request->input('app');

        $modelClass = "Modules\\" . $app . "\\Entities\\" . $modelName;
        if (class_exists($modelClass)) {
            $find = $modelClass::orderBy('id')->paginate(100)->onEachSide(1)->appends(request()->query());
            return Response::json(['status' => true, 'body' => $find], 200);
        }

        return Response::json(['status' => false, 'body' => 'no results'], 401);
    }
}
