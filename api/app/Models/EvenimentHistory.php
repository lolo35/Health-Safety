<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EvenimentHistory extends Model
{
    use HasFactory;
    protected $table = "eveniment_histories";
    protected $fillable = ['division_id', 'dispatch_number', 'dispatch_id', 'type', 'description', 'reason', 'line', 'area'];
    protected $hidden = [];

    public function insert(int $division_id, array $data, string $reason = null) {
        try {
            $insert = new $this();
            $insert->division_id = $division_id;
            $insert->dispatch_number = $data['dispatchnumber'];
            $insert->dispatch_id = $data['id'];
            $insert->type = $data['dispatchtypecode'];
            $insert->description = $data['description'];
            $insert->reason = $reason;
            $insert->line = $data['line'];
            $insert->area = $data['area'];
            $insert->save();

            return ['success' => true, 'data' => $insert];
        } catch (Exception $e) {
            Log::error("Error inserting eveniment history", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
