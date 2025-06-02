<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connector extends Model
{
    use HasFactory;

    protected $fillable = [
        'charging_station_id',
        'connector_id',
        'status',
        'error_code',
        'info',
        'vendor_id',
        'vendor_error_code'
    ];

    public function chargingStation()
    {
        return $this->belongsTo(ChargingStation::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'connector_id', 'connector_id')
            ->where('charging_station_id', $this->charging_station_id);
    }
}
