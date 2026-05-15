<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Clients/Index', [
            'clients' => Client::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'document' => ['required'],
            'email' => ['required', 'email'],
            'status' => ['required'],
        ]);

        $client = Client::create($request->all());

        return response()->json([
            'success' => true,
            'client' => $client
        ]);
    }

    public function show(Client $client)
    {
        return response()->json($client);
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => ['required'],
            'document' => ['required'],
            'email' => ['required', 'email'],
            'status' => ['required'],
        ]);

        $client->update($request->all());

        $client->refresh();

        return response()->json([
            'success' => true,
            'client' => $client
        ]);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json([
            'success' => true
        ]);
    }
}