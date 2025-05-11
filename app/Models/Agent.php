<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'licence_no',
        'agency',
        'experience',
        'bio',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function conversation()
    {
        return $this->hasMany(Conversation::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
