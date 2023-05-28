<?php

namespace Tests\Unit\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use App\Services\LoanService;
use App\Models\LoanStatuses;
use Laravel\Sanctum\Sanctum;
use App\Http\Controllers\LoanController;
use Mockery;

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $loanService;

    public function setUp(): void
    {
        parent::setUp();

        // Mock the LoanService
        $this->loanService = Mockery::mock(LoanService::class);
        $this->app->instance(LoanService::class, $this->loanService);
    }

    public function testCreateLoan()
    {
        $user = User::factory()->create();
        $request = new \Illuminate\Http\Request([
            'amount' => 1000,
            'term_duration' => 30
        ]);

        $loanStatus = LoanStatuses::factory()->create();
        $expectedLoan = Loan::factory()->create();

        // Mock the LoanService's createLoan method
        $this->loanService->shouldReceive('createLoan')
            ->with($user->id, $request['amount'], $request['term_duration'])
            ->andReturn($expectedLoan);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $controller = new LoanController($this->loanService);
        $response = $controller->create($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetAllForUser()
    {
        $user = User::factory()->create();
        $loanStatus = LoanStatuses::factory()->create();
        $loans = Loan::factory()->count(3)->create();

        // Mock the LoanService's getLoanById method
        $this->loanService->shouldReceive('getLoansForUser')
            ->with($user->id)
            ->andReturn($loans);

        $request = new \Illuminate\Http\Request([]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $controller = new LoanController($this->loanService);
        $response = $controller->getAllForUser($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetById()
    {
        $user = User::factory()->create();
        $loanStatus = LoanStatuses::factory()->create();
        $loan = Loan::factory()->create();

        // Mock the LoanService's getLoanById method
        $this->loanService->shouldReceive('getLoanById')
            ->with($loan->id, $user->id)
            ->andReturn($loan);
        $request = new \Illuminate\Http\Request([]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $controller = new LoanController($this->loanService);
        $response = $controller->getById($request, $loan->id);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testApprove()
    {
        $loanId = 1;
        $loanStatus = LoanStatuses::factory()->create();
        $loan = Loan::factory()->create(['status_id' => LoanStatuses::PENDING]);

        // Mock the LoanService's approveLoan method
        $this->loanService->shouldReceive('approveLoan')
            ->with($loanId)
            ->andReturn($loan);

        $request = new \Illuminate\Http\Request([]);

        $controller = new LoanController($this->loanService);
        $response = $controller->approve($request, $loanId);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetAll()
    {
        $loanStatus = LoanStatuses::factory()->create();
        $expectedLoans = Loan::factory()->count(5)->create();

        // Mock the LoanService's getAllLoans method
        $this->loanService->shouldReceive('getAllLoans')
            ->andReturn($expectedLoans);

        $request = new \Illuminate\Http\Request([]);

        $controller = new LoanController($this->loanService);
        $response = $controller->getAll($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

}