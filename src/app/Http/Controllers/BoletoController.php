<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoletoRequest;
use App\Http\Requests\UpdateBoletoRequest;
use App\Models\Boleto;
use App\Services\BoletoService;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class BoletoController extends Controller
{
    private BoletoService $boletoService;
    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }

    public function generate()
    {
        return response('', Response::HTTP_CREATED);
    }
}
