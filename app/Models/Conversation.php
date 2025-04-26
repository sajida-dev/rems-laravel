<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'agent_id',
        'property_id',
        'subject',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function agents()
    {
        return $this->belongsToMany(Agent::class);
    }
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
