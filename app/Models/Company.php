<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function teams()
    {
        /*contoh relasi yang digunakan ketikan tabel/kolom tidak sesuai format laravel */
        return $this->hasMany(Team::class, 'company_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
