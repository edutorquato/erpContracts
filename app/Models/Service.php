<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'monthly_base_value',
    ];

    public function contractItems()
    {
        return $this->hasMany(ContractItem::class);
    }
}