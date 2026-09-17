<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    use HasFactory;

    protected $fillable = ['main_module_id', 'name'];

    public function mainModule()
    {
        return $this->belongsTo(MainModule::class);
    }

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }
}
