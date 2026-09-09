<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function facilities()
    {
    }

    public function facilityAvailability($id)
    {
    }
}
