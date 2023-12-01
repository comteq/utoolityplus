<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedules extends Model
{
    use HasFactory;
    protected $table = 'schedule';
    protected $fillable = ['event_datetime' ,'event_datetime_off', 'description', 'state'];
    protected $casts = [
        'event_datetime' => 'datetime',
        'event_datetime_off' => 'datetime',
    ];

    public function activities()
    {
        return $this->hasMany(activity::class, 'Schedule_ID');
    }
}

