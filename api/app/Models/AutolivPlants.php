<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AutolivPlants extends Model
{
    use HasFactory;
    protected $table = "autoliv_plants";
    protected $fillable = ['abbreviation', 'l2l_url'];
    protected $hidden = ['l2l_auth', 'l2l_site'];

    public function fetchAllData(bool $visible = false):array {
        try {
            if(!$visible) {
                $data = $this->get()->toArray();
                return ['success' => true, 'data' => $data];
            }
            $data = $this->get()->makeVisible(['l2l_auth', 'l2l_site'])->toArray();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Error fetching all data", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function fetchPlantById(int $plant_id, bool $visible = false):array {
        try {
            if(!$visible) {
                $data = $this->where('id', '=', $plant_id)->first()->toArray();
                return ['success' => true, 'data' => $data];
            }
            $data = $this->where('id', '=', $plant_id)->first()->makeVisible(['l2l_auth', 'l2l_site'])->toArray();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Error fethching plant by ID", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
