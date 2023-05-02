<?php

namespace App\Models\Itender;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ItenderTerms extends Model 
{
    protected $table = 'itender_terms';
    
    protected $fillable = ['name'];

}
