<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SafetyEvents extends Model
{
    use HasFactory;
    protected $table = "safety_events";
    protected $fillable = ['division_id', 'l2l_id', 'code', 'description', 'has_reasons', 'btn_color', 'remains_open'];
    protected $hidden = [];

    public function insert(int $division_id, array $data, bool $hasReasons = false, string $btnColor = "bg-red-500", bool $remainsOpen = true) {
        try {
            $insert = new $this();
            $insert->division_id = $division_id;
            $insert->l2l_id = $data['id'];
            $insert->code = $data['code'];
            $insert->description = $data['description'];
            $insert->has_reasons = $hasReasons;
            $insert->btn_color = $btnColor;
            $insert->remains_open = $remainsOpen;
            $insert->save();
            return ['success' => true, 'data' => $insert];
        } catch (Exception $e) {
            Log::error("Error inserting safety event", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getEventsForDivision(int $division_id, bool $hasReasons = false) {
        try {
            $data = $this::where('division_id', '=', $division_id)->where('has_reasons', '=', $hasReasons)->get();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Error fetching events for division", $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
