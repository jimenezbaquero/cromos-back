<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function redirect(){
        $user = Auth::user();
        if($user->hasRole('admin')){
            return Inertia::render('Admin/Dashboard', []);
        }
        if($user->hasRole('client')){
            return Inertia::render('Client/Dashboard', []);
        }
        return redirect()->route('home');
    }
}
