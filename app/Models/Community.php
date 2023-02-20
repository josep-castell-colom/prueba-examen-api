<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Community extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'rules'
    ];

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }
}
