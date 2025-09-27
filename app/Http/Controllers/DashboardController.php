<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboards.admin');
    }

    public function crm()
    {
        return view('dashboards.crm');
    }

    public function doctor()
    {
        return view('dashboards.doctor');
    }

    public function patient()
    {
        return view('dashboards.patient');
    }

    public function lab()
    {
        return view('dashboards.lab');
    }
}
