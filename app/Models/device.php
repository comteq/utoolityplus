<?php

// Device.php (Model)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device'; // Specify the exact table name
    public $timestamps = false; // Disable timestamps

    protected $fillable = ['Pin_Number', 'Device_IP'];

    // Define the relationship with the User model if necessary
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}