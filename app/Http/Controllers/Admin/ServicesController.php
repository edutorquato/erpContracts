<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicesController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'monthly_base_value' => ['required', 'numeric'],
        ]);

        $service = Service::create($request->all());

        return response()->json([
    'success' => true,
    'service' => $service
]);


    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => ['required'],
            'monthly_base_value' => ['required', 'numeric'],
        ]);

        $service->update($request->all());

$service->refresh();

return response()->json([
    'success' => true,
    'service' => $service
]);

    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'success' => true
        ]);
    }
}