<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {

        // Aqui você pode buscar qualquer dado que quiser do banco
        $user = auth()->user(); // usuário logado
        $stats = [
            'posts' => 120,
            'users' => 50,
            'sales' => 30,
        ];

        // Retornando para o Vue via Inertia
        return Inertia::render('Admin/Dashboard/Index', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

}