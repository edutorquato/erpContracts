<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContractsController extends Controller
{
    public function index()
    {
        $contracts = Contract::with([
            'client',
            'items.service'
        ])
        ->latest()
        ->get();

        return Inertia::render('Admin/Contracts/Index', [
            'contracts' => $contracts,
            'clients' => Client::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => ['required'],
            'start_date' => ['required'],
            'status' => ['required'],
            'items' => ['required', 'array'],
        ]);

        $contract = Contract::create([
            'client_id' => $request->client_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        foreach ($request->items as $item) {

            $contract->items()->create([
                'service_id' => $item['service_id'],
                'quantity' => $item['quantity'],
                'unit_value' => $item['unit_value'],
            ]);
        }

$contract->load([
    'client',
    'items.service'
]);

return response()->json([
    'success' => true,
    'contract' => $contract
]);
    }

    public function update(Request $request, Contract $contract)
{
    // REGRA DE NEGÓCIO
    // Contrato cancelado não pode editar

    if ($contract->status == 'cancelled') {

        return response()->json([
            'message' => 'Contrato cancelado não pode ser editado.'
        ], 422);
    }

    $request->validate([
        'client_id' => ['required'],
        'start_date' => ['required'],
        'status' => ['required'],
        'items' => ['required', 'array'],
    ]);

    // Atualiza contrato

    $contract->update([
        'client_id' => $request->client_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => $request->status,
    ]);

    // Remove itens antigos

    $contract->items()->delete();

    // Recria itens

    foreach ($request->items as $item) {

        $contract->items()->create([
            'service_id' => $item['service_id'],
            'quantity' => $item['quantity'],
            'unit_value' => $item['unit_value'],
        ]);
    }

$contract->load([
    'client',
    'items.service'
]);

return response()->json([
    'success' => true,
    'contract' => $contract
]);
}

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return response()->json([
            'success' => true
        ]);
    }
}