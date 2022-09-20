<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AutolivPlants;
use App\Models\Departaments;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FetchDepartaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'departaments:copy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies L2L departaments for all plants';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $plants = $this->plantsInfo();
            foreach($plants['data'] as $plant) {
                $save = $this->saveDepartaments($plant);
                if($save['success']) {
                    $this->info("Saved departaments for " . $plant['abbreviation']);
                }
            }
        } catch (Exception $e) {
            Log::error("Error in departaments command", ['error' => $e->getMessage()]);
            $this->error($e->getMessage());
        }
        return 0;
    }

    private function plantsInfo():array {
        try {
            $plants = new AutolivPlants();
            $data = $plants->fetchAllData(true);

            return ['success' => true, 'data' => $data['data']];
        } catch (Exception $e) {
            Log::error("Error fetching plants info", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function saveDepartaments(array $plant):array {
        try {
            $url = $plant['l2l_url'] . "areas/?auth=" . $plant['l2l_auth'] . "&site=" . $plant['l2l_site'] . "&fields=id,code,description&active=true";
            $request = Http::get($url);
            $response = $request->json();
            $error = false;
            if($response['success']) {
                foreach($response['data'] as $departament) {
                    $departaments = new Departaments();
                    $insert = $departaments->insert($departament, $plant['id']);
                    if(!$insert['success']) {
                        $error = true;
                    }
                }
            }
            if($error) {
                return ['success' => false];
            }
            return ['success' => true];
        } catch (Exception $e) {
            Log::error("Error saving departaments", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
