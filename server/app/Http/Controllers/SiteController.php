<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Facade para envio de email
use Illuminate\Support\Facades\Validator; // Facade para validação

class SiteController extends Controller
{
    public function index()
    {
        return view('site.home.index');
    }

    public function loginPage()
    {
        return view('site.login.index');
    }

}