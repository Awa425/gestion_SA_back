<?php

namespace App\Models;

use App\Models\Module;
use App\Models\Apprenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;



    protected $fillable = ['apprenant_id', 'module_id', 'prof_id', 'note'];

    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class, 'apprenant_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'prof_id');
    }

}
