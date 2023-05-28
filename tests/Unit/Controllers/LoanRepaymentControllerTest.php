<?php

namespace Tests\Unit\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\LoanRepaymentController;
use App\Models\User;
use App\Models\Loan;
use App\Models\LoanStatuses;
use App\Models\LoanRepayment;
use App\Services\LoanRepaymentService;
use Illuminate\Http\Request;
use Mockery;

class LoanRepaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $loanRepaymentService;

    public function setUp(): void
    {
        parent::setUp();
        $this->loanRepaymentService = Mockery::mock(LoanRepaymentService::class);
        $this->app->instance(LoanRepaymentService::class, $this->loanRepaymentService);

    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testCreateRepayment()
    {
        $user = User::factory()->create();
        $loanStatus = LoanStatuses::factory()->create(['id' => LoanStatuses::APPROVED]);
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'status_id' => LoanStatuses::APPROVED,
            'amount'   => 2000,
            'term_duration' => 2
        ]);

        $request = new \Illuminate\Http\Request([
            'amount' => 501
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $expectedRepayment = LoanRepayment::factory()->create([
            "loan_id" => $loan->id,
            "amount"   => $request['amount']
        ]);

        // Mock the LoanRepaymentService's createRepayment method
        $this->loanRepaymentService->shouldReceive('createRepayment')
            ->with($loan->id, $request['amount'], $user->id)
            ->andReturn($expectedRepayment);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $controller = new LoanRepaymentController($this->loanRepaymentService);
        $response = $controller->create($request, $loan->id);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }


    public function testCreateRepaymentWithInvalidAmount()
    {
        $user = User::factory()->create();
        $loanStatus = LoanStatuses::factory()->create(['id' => LoanStatuses::APPROVED]);
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'status_id' => LoanStatuses::APPROVED
        ]);
        $requestData = [
            'amount' => -1000
        ];

        $request = Request::create('/api/loans/' . $loan->id . '/repayments', 'POST', $requestData);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Mock the LoanRepaymentService's createRepayment method to throw an exception
        $this->loanRepaymentService->shouldReceive('createRepayment')
            ->andThrow(new \InvalidArgumentException("Invalid amount"));

        $controller = new LoanRepaymentController($this->loanRepaymentService);
        $response = $controller->create($request, $loan->id);

        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
    }
}
