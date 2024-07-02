<?php

namespace App\Http\Controllers;

use App\Models\ServiceActivate;
use App\Models\ServiceRenew;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function activate()
    {
        try {
            $items = ServiceActivate::where('api_call_status', '-1')->get();
            foreach ($items as $item) {
                $data = [
                    'token' => env('API_CALL_TOKEN'),
                    'peer_id' => $item->service->panel_peer_id,
                ];

                $api_call = api_call(
                    'POST', 
                    env('PANEL_URL').'/wiregaurd/peers/renew', 
                    json_encode($data), 
                    true
                );

                // insert in service_renews
                ServiceActivate::where('id', $item->id)
                    ->update([
                        'api_call_status' => $api_call['status'],
                        'api_call_message' => $api_call['message'],
                    ]);
            }
        } catch (\Exception $exception) {
            //
        }
    }
    public function renew()
    {
        try {
            $items = ServiceRenew::where('api_call_status', '-1')->where('payment_status', 'successful')->get();
            foreach ($items as $item) {
                $data = [
                    'token' => env('API_CALL_TOKEN'),
                    'peer_id' => $item->service->panel_peer_id,
                    'add_days' => $item->add_days
                ];

                $api_call = api_call(
                    'POST', 
                    env('PANEL_URL').'/wiregaurd/peers/renew', 
                    json_encode($data), 
                    true
                );

                // insert in service_renews
                ServiceRenew::where('id', $item->id)
                    ->update([
                        'api_call_status' => $api_call['status'],
                        'api_call_message' => $api_call['message'],
                    ]);
            }
        } catch (\Exception $exception) {
            //
        }
    }
    public function toggleEnable()
    {
        try {
            $items = ServiceToggleEnable::where('api_call_status', '-1')->get();
            foreach ($items as $item) {
                $data = [
                    'token' => env('API_CALL_TOKEN'),
                    'peer_id' => $item->service->panel_peer_id,
                    'status' => $item->status
                ];

                $api_call = api_call(
                    'POST', 
                    env('PANEL_URL').'/wiregaurd/peers/toggleEnable', 
                    json_encode($data), 
                    true
                );

                // insert in service_renews
                ServiceToggleEnable::where('id', $item->id)
                    ->update([
                        'api_call_status' => $api_call['status'],
                        'api_call_message' => $api_call['message'],
                    ]);
            }
        } catch (\Exception $exception) {
            //
        }
    }
}
