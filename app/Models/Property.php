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
        'year_built',
        'is_water',
        'stories',
        'is_new_roofing',
        'garage',
        'is_luggage',
        'latitude',
        'longitude',
        'image_url',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class)->withTimestamps();
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }
    public function bookmark()
    {
        return $this->hasMany(Bookmark::class);
    }
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function isForRent()
    {
        return $this->type === 'rent';
    }

    public function isForBuy()
    {
        return $this->type === 'buy';
    }
}
