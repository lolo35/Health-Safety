<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Departaments extends Model
{
    use HasFactory;
    protected $table = "departaments";
    protected $fillable = ['division_id', 'l2l_id', 'code', 'description'];
    protected $hidden = [];

    public function insert(array $data, int $division_id):array {
        try {
            $insert = new $this();
            $insert->division_id = $division_id;
            $insert->l2l_id = $data['id'];
            $insert->code = $data['code'];
            $insert->description = $data['description'];

            $insert->save();

            return ['success' => true, 'data' => $insert];
        } catch (Exception $e) {
            Log::error("Error saving departament", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function fetchDepartamentsForDivision(int $division_id) {
        try {
            $data = $this::where('division_id','=', $division_id)->get();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Error fetching departaments for division", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
