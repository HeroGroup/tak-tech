<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function dbMigrate($rollback=false)
    {
        try {
            if ($rollback) {
                Artisan::call('migrate:rollback');
            } else {
                Artisan::call('migrate');
            }
            
            return Artisan::output();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        
    }
}
