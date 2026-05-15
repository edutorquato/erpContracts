<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractItem;
use Illuminate\Http\Request;

class ContractItemsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => ['required'],
            'service_id' => ['required'],
            'quantity' => ['required', 'integer'],
            'unit_value' => ['required', 'numeric'],
        ]);

        $item = ContractItem::create($request->all());

        return response()->json([
            'success' => true,
            'item' => $item
        ]);
    }

    public function update(Request $request, ContractItem $contractItem)
    {
        $contractItem->update($request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy(ContractItem $contractItem)
    {
        $contractItem->delete();

        return response()->json([
            'success' => true
        ]);
    }
}