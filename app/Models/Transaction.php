<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'charging_station_id',
        'connector_id',
        'transaction_id',
        'id_tag',
        'start_time',
        'stop_time',
        'start_meter_value',
        'stop_meter_value',
        'stop_reason'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'stop_time' => 'datetime'
    ];

    public function chargingStation()
    {
        return $this->belongsTo(ChargingStation::class);
    }

    public function meterValues()
    {
        return $this->hasMany(MeterValue::class);
    }

    public function getDurationAttribute()
    {
        if (!$this->stop_time) {
            return $this->start_time->diffForHumans(now(), true);
        }

        return $this->start_time->diffForHumans($this->stop_time, true);
    }

    public function getEnergyConsumedAttribute()
    {
        if (!$this->stop_meter_value) {
            return null;
        }

        return $this->stop_meter_value - $this->start_meter_value;
    }
}
