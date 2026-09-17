<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = ['sub_module_id', 'name', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }
}
