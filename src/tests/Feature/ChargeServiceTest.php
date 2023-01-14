<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\WebhookRequest;
use App\Services\ChargeService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;

class ChargeServiceTest extends TestCase
{
    private ChargeService $chargeService;
    private WebhookRequest $webhookRequest;

    public function setup(): void
    {
        parent::setup();
        $this->chargeService = resolve('App\Services\ChargeService');
        $this->webhookRequest = resolve('App\Http\Requests\WebhookRequest');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @expectedException Exception
     */
    public function test_not_found_charge_should_return_an_exception_with_code_400(): void
    {
        $this->mock(Charge::class, function (MockInterface $mock) {
            $mock->shouldReceive('where')->andReturn(null);
        });

        $this->webhookRequest
            ->setDebtId(1);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->chargeService->payCharge($this->webhookRequest);
    }
}
