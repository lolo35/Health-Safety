<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lines;
use Exception;
use Illuminate\Support\Facades\Log;

class LinesController extends Controller
{
    public function fetchLinesForDepartament(Request $request) {
        try {
            $this->validate($request, [
                'area_id' => "required|integer"
            ]);

            $lines = new Lines();
            $data = $lines->fetchLinesForDepartament($request->area_id);
            
            return response()->json(['success' => true, 'data' => $data['data']], 200);
        } catch (Exception $e) {
            Log::error("Controller: Error fething lines for departament", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }
}
