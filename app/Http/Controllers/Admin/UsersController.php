<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{

    public function index()
    {

        $user = auth()->user();

        return Inertia::render('Admin/Users/Index', [
            'user' => $user,
        ]);

    }

}