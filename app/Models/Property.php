<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
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
        'category_id',
        'agent_id',
        'title',
        'description',
        'location',
        'rent_price',
        'purchase_price',
        'old_rent_price',
        'old_purchase_price',
        'bedrooms',
        'bathrooms',
        'lot_area',
        'floor_area',
        'is_water',
        'is_new_roofing',
        'garage',
        'is_luggage',
        'image_url',
    ];
    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }
    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    public function upload()
    {
        return $this->hasMany(Upload::class);
    }
}
