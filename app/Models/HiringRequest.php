<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiringRequest extends Model
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
        'request_type',
        'category_id',
        'location',
        'min_budget',
        'max_budget',
        'bedrooms',
        'requirements',
        'description',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function agents()
    {
        return $this->belongsToMany(Agent::class);
    }
}
