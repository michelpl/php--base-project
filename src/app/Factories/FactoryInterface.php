<?php

namespace App\Factories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface FactoryInterface
{
    public function createFromRequestData(Request $requestData): Model;
}