<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function employees(){
        return $this->belongsToMany(Employee::class)->withTimestamps();
    }
}
