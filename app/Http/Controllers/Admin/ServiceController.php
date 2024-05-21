<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
