<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function dbMigrate($token, $rollback=false)
    {
        try {
            if ($token == env('MIGRATION_TOKEN')) {
                if ($rollback) {
                    Artisan::call('migrate:rollback');
                } else {
                    Artisan::call('migrate');
                }
            } else {
                return "invalid token!";
            }
            
            return Artisan::output();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        
    }
}
