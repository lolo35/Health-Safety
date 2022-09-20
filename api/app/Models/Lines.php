<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Lines extends Model
{
    use HasFactory;
    protected $table = "lines";
    protected $fillable = ['division_id', 'code', 'areacode', 'area', 'l2l_line_id', 'description', 'default_machine_id', 'default_machine_code'];
    protected $hidden = [];

    public function insert(array $line, int $division_id, bool $return = false, string $defaultMachineCode = ""):array {
        try {
            $insert = new $this();
            $insert->division_id = $division_id;
            $insert->code = $line['code'];
            $insert->areacode = $line['areacode'];
            $insert->area = $line['area'];
            $insert->l2l_line_id = $line['id'];
            $insert->description = $line['description'];
            $insert->default_machine_id = $line['defaultmachine'];
            $insert->default_machine_code = $defaultMachineCode;
            $insert->save();
            
            if(!$return) {
                return ['success' => true];
            }
            return $insert;
        } catch (Exception $e) {
            Log::error("Error inserting line", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function fetchLinesForDepartament(int $areaid):array {
        try {
            $data = $this::where('area', '=', $areaid)->get();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Model: Error fetching lines for departament", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function fetchLineById(int $id):array {
        try {
            $data = $this::where('id', '=', $id)->first()->toArray();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Error fetching line by ID", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
