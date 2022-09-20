<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\AutolivPlants;
use App\Models\Lines;
use Exception;

class CopyL2LLines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lines:copy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies all lines from l2l into local database';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function __construct()
    {
        parent::__construct();
        $this->l2l_url = "";
        $this->l2l_auth = "";
        $this->l2l_site = "";
    }

    public function handle()
    {
        $this->info("Fetching Autoliv Plants Info");
        $autolivPlants = new AutolivPlants();

        $plants = $autolivPlants->fetchAllData(true);
        if($plants['success']) {
            foreach($plants['data'] as $plant) {
                $this->l2l_url = $plant['l2l_url'];
                $this->l2l_auth = $plant['l2l_auth'];
                $this->l2l_site = $plant['l2l_site'];

                $url = $this->l2l_url . "lines/?auth=" . $this->l2l_auth . "&site=" . $this->l2l_site . "&active=true&fields=id,site,code,areacode,description,area,defaultmachine&limit=1000";

                $request = Http::get($url);
                $response = $request->json();
                if($response['success']) {
                    $save = $this->saveLines($response['data'], $plant['id']);
                    if($save['success']) {
                        $this->info("Saved successfully for plant: " . $plant['abbreviation']);
                    }
                }
            }
        }
        return 0;
    }

    private function saveLines(array $lines, int $division_id):array {
        try {
            foreach($lines as $line) {
                //print_r($line); exit();
                $machineCode = $this->getMachineCode($line['defaultmachine']);
                $insert = new Lines();
                $insert = $insert->insert($line, $division_id, false, $machineCode['data']['code']);
            }
            return ['success' => true];
        } catch (Exception $e) {
            Log::error("Error saving lines", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function getMachineCode(int $machineId): array {
        try {
            $url = $this->l2l_url . "machines/?auth=" . $this->l2l_auth . "&site=" . $this->l2l_site . "&fields=id,code,description&id=$machineId";

            $request = Http::get($url);
            $response = $request->json();
            if($response['success']) {
                return $response;
            }
            return ['success' => false, 'error' => $response['error']];
        } catch (Exception $e) {
            Log::error("Error fetching machine code", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
