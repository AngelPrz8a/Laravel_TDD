<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){
        return view("welcome",[
            "repositories"=>Repository::latest()->paginate(),
        ]);
    }
}
