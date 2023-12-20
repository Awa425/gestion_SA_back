<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;


    public function notes()
    {
        return $this->hasMany(Note::class, 'module_id');
    }
}
