<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function responbilities()
    {
        return $this->hasMany(Responsibility::class);
    }

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
