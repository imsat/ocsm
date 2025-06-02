<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargingStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'vendor',
        'model',
        'serial_number',
        'firmware_version',
        'status',
        'last_heartbeat',
        'connector_count'
    ];

    protected $casts = [
        'last_heartbeat' => 'datetime'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function connectors()
    {
        return $this->hasMany(Connector::class);
    }

    public function isOnline()
    {
        return $this->last_heartbeat &&
            $this->last_heartbeat->diffInMinutes(now()) < 10;
    }
}
