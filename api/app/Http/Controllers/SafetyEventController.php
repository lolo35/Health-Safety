<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SafetyEvents;
use App\Models\EvenimentHistory;
use App\Models\Lines;
use App\Models\AutolivPlants;
use App\Models\EventReasons;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;

class SafetyEventController extends Controller
{
    public function getEvents(Request $request) {
        try {
            $this->validate($request, [
                'division_id' => "required|integer",
                'with_reason' => 'required|boolean'
            ]);

            $events = new SafetyEvents();
            $data = $events->getEventsForDivision($request->division_id, $request->with_reason);
            return response()->json(['success' => true, 'data' => $data['data']], 200);
        } catch (Exception $e) {
            Log::error("Error getting events", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function openEvent(Request $request) {
        try {
            $this->validate($request,
                [
                    'division_id' => "required|integer",
                    'line' => "required|integer",
                    'departament' => "required|integer",
                    'departament_id' => "required|integer",
                    'event_type' => "required|string",
                    'event_id' => "required|integer",
                    'description' => "required|string"
                ]
            );


            $line = new Lines();
            $line = $line->fetchLineById($request->input('line'));

            $plant = new AutolivPlants();
            $plant = $plant->fetchPlantById($request->input('division_id'), true);
            if($plant['success'] && $line['success'])  {
                $fields = (array) $request->all();
                $endpoint = "open/";

                $machineKey = "machine";
                $machineValue = $line['data']['default_machine_id'];
                $dispatchTypeKey = "dispatchtype";
                $dispatchValue = $request->input('event_id');
                $userKey = "user";

                if(!$request->input('remains_open')) {
                    $endpoint = "add/";
                    $machineKey = "machinecode";
                    $machineValue = $line['data']['default_machine_code'];
                    $dispatchTypeKey = "dispatchtypecode";
                    $dispatchValue = $request->input('event_type');
                    $userKey = "resource";
                }
                $url = $plant['data']['l2l_url'] . "dispatches/$endpoint?auth=" . $plant['data']['l2l_auth'] . "&site=" . $plant['data']['l2l_site'];

                //return response()->json(['success' => true, 'url' => $url, 'remains_open' => $request->input('remains_open')], 200);
                $headers = [
                    'Content-type' => 'application/x-www-form-urlencoded'
                ];

                $body = [
                    'auth' => $plant['data']['l2l_auth'],
                    'site' => $plant['data']['l2l_site'],
                    $dispatchTypeKey => $dispatchValue,
                    'description' => $request->input('description'),
                    $machineKey => $machineValue,
                    'tradecode' => "OHS", //this needs to be dynamic
                    //$userKey => "TRO.ZeroFinding"
                ];
                
                if(isset($fields['reason_id'])) {
                    //return response()->json(['success' => true, 'fields' => $fields, 'in_array' => true], 200);
                    if($request->input('remains_open')) {
                        $body = array_merge($body, ['reason_id' => $request->input('reason_id')]);
                        $body = array_merge($body, [$userKey => "TRO.ZeroFinding"]);
                    } else {
                        $body = array_merge($body, ['reasoncode' => $request->input('reasoncode')]);
                    }
                }
                //return response()->json(['success' => true, 'fields' => $fields, 'in_array' => false], 200);
                $req = Http::asForm()->withHeaders($headers)->post($url, $body);

                $response = $req->json();

                if($response['success']) {
                    $history = new EvenimentHistory();
                    $history->insert($request->input('division_id'), $response['data']);
                }

                return response()->json(['success' => true, 'response' => $response, 'body' => $body], 200);
            }

        } catch (Exception $e) {
            Log::error("Error opening event", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }

    public function fetchReasonsForEvent(Request $request) {
        try {
            $this->validate($request, [
                'event_id' => "required|integer"
            ]);

            $reasons = new EventReasons();
            $reasons = $reasons->fetchReasonsForEvent($request->event_id);
            return response()->json(['success' => true, 'data' => $reasons['data']], 200);
        } catch (Exception $e) {
            Log::error("Controller: error fetching reasons for event", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
