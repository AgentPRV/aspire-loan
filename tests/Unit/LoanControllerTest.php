<?php

namespace Tests\Unit;

use App\Http\Controllers\LoanController;
use App\Models\LoanStatuses;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoanControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    use WithFaker;

    public function testCreateLoan()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $request = new \Illuminate\Http\Request([
            'amount' => 1000,
            'term_duration' => 30
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $loanStatus = LoanStatuses::factory()->create();

        $controller = new LoanController();
        $response = $controller->create($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'amount' => 1000,
            'term_duration' => 30,
            'status_id' => LoanStatuses::PENDING
        ]);
    }
}
