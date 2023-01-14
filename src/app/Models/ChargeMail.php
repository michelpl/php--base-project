<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChargeMail extends Model
{
    protected $fillable = [
        'customerEmailAdress',
        'emailBody',
    ];
    //private string $customerEmailAddress;
    //private string $emailBody;
}
