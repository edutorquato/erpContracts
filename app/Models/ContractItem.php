<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractItem extends Model
{
    protected $fillable = [
        'contract_id',
        'service_id',
        'quantity',
        'unit_value',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}