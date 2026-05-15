<?php

namespace App\Services;

class ContractCalculatorService
{
    public function calculate($items)
    {
        $subtotal = 0;

        foreach ($items as $item) {

            $subtotal += (
                $item->quantity * $item->unit_value
            );
        }

        // REGRA DE NEGÓCIO
        // 10% desconto acima de 1000

        if ($subtotal >= 1000) {

            $subtotal = $subtotal * 0.90;
        }

        return round($subtotal, 2);
    }
}