<?php

namespace App\Http\Controllers;

use App\Models\ServiceRenew;
use Illuminate\Http\Request;

class JobController extends Controller
{
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
}
