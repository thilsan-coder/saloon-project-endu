<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainModule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'description'];

    public function subModules()
    {
        return $this->hasMany(SubModule::class);
    }
}
