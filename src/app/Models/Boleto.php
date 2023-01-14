<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'government_id',
        'charge_id',
        'amount',
        'debt_due_date',
    ];
}