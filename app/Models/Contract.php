<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\ContractCalculatorService;

class Contract extends Model
{
    protected $fillable = [
        'client_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $appends = [
        'total'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(ContractItem::class);
    }

    public function getTotalAttribute()
    {
        return app(
            ContractCalculatorService::class
        )->calculate($this->items);
    }

}