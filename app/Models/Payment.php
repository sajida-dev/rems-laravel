<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
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
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'environment',
        'stripe_payment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function authorize(User $user)
    {
        // Admin can do anything
        if ($user->role === 'admin') {
            return true;
        }

        // Agent can only view payments for their properties
        if ($user->role === 'agent') {
            return $this->property->user_id === $user->id;
        }

        // Regular users can only view their own payments
        return $this->user_id === $user->id;
    }

    public function transaction()
    {
        return $this->belongsToMany(Transaction::class);
    }
}
