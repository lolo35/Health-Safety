<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\Departaments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartamentsController extends Controller
{
    public function fetchDepartamentsForDivision(Request $request) {
        try {
            $this->validate($request, [
                'division_id' => "required|integer"
            ]);

            $departaments = new Departaments();
            $departaments = $departaments->fetchDepartamentsForDivision($request->division_id);
            return response()->json(['success' => true, 'data' => $departaments['data']], 200);
        } catch (Exception $e) {
            Log::error("Controller: Error fetching departaments for division ", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }
}
