<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'timestamp',
        'sampled_values'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'sampled_values' => 'array'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
