<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceToggleEnable;
use Illuminate\Http\Request;

require_once app_path('Helpers/utils.php');

class ServiceController extends Controller
{
    // Display a listing of Services
    public function index(Request $request)
    {
        try {
            $is_sold = $request->query('is_sold');
            if (in_array($is_sold, ['0', '1'])) {
                $services = Service::where('is_sold', $is_sold)->get();
            } else {
                $services = Service::get();
            }

            $page = $request->query('page', 1);
            $take = $request->query('take', 50);
            if ($take == 'all') {
                $isLastPage = true;
            } else {
                $skip = ($page - 1) * $take;
                $services = $services->skip($skip)->take($take);
                $isLastPage = (count($services) < $take) ? true : false;
            }
            $numberOfSoldServices = Service::where('is_sold', 1)->count();
            $numberOfFreeServices = Service::where('is_sold', 0)->count();

            return view('admin.services', compact('services', 'numberOfSoldServices', 'numberOfFreeServices', 'isLastPage'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    // enable / disable service
    public function toggleEnable(Request $request)
    {
        try {
            $service = Service::find($request->id);
            if (!$service) {
                return $this->fail("invalid service");
            }
            $status = $request->status;

            $service->is_enabled = $status;
            $service->save();

            $data = [
                'token' => env('API_CALL_TOKEN'),
                'peer_id' => $service->panel_peer_id,
                'status' => $status
            ];

            $api_call = api_call(
                'POST', 
                env('PANEL_URL').'/wiregaurd/peers/toggleEnable', 
                json_encode($data), 
                true
            );
            
            ServiceToggleEnable::create([
                'service_id' => $request->id,
                'status' => $request->status,
                'api_call_status' => $api_call['status'],
                'api_call_message' => $api_call['message']
            ]);

            return $this->success("success!");
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }
}
