<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\UserLog;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use CanResetPassword;
    use Notifiable;

    protected $dates = ['deleted_at'];



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'contact',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Check if the user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function agent()
    {
        return $this->hasOne(Agent::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    public function hiringRequests()
    {
        return $this->hasMany(HiringRequest::class);
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }
    public function unreadMessages()
    {
        return $this->receivedMessages()->whereNull('read_at');
    }
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function lastSeen()
    {
        if ($this->isOnline()) {
            return 'Online';
        }

        $lastActivity = DB::table('sessions')
            ->where('user_id', $this->id)
            ->orderBy('last_activity', 'desc')
            ->first();

        if (!$lastActivity) {
            return 'Never active';
        }

        $lastSeen = \Carbon\Carbon::createFromTimestamp($lastActivity->last_activity);
        $now = \Carbon\Carbon::now();

        if ($lastSeen->diffInMinutes($now) < 1) {
            return 'Just now';
        } elseif ($lastSeen->diffInMinutes($now) < 60) {
            return $lastSeen->diffInMinutes($now) . ' minutes ago';
        } elseif ($lastSeen->diffInHours($now) < 24) {
            return $lastSeen->diffInHours($now) . ' hours ago';
        } else {
            return $lastSeen->format('M d, Y H:i');
        }
    }
}
