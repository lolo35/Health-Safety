<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EventReasons extends Model
{
    use HasFactory;
    protected $table = "event_reasons";
    protected $fillable = ['division_id', 'l2l_id', 'code', 'description', 'belongs_to'];
    protected $hidden = [];

    public function insert(int $division_id, array $data, int $belongsTo = 0):array {
        try {
            $insert = new $this();
            $insert->division_id = $division_id;
            $insert->l2l_id = $data['id'];
            $insert->code = $data['code'];
            $insert->description = $data['description'];
            if($belongsTo !== 0) {
                $insert->belongs_to = $belongsTo;
            }

            $insert->save();

            return ['success' => true, 'data' => $insert];
        } catch (Exception $e) {
            Log::error("Error inserting event reasons", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function fetchReasonsForEvent(int $event_id) {
        try {
            $data = $this::where('belongs_to', '=', $event_id)->get()->toArray();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Error fetching reasons for event", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
