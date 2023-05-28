<?php

namespace Tests\Unit\Services;

use App\Models\Loan;
use App\Models\User;
use App\Models\LoanStatuses;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    private $loanService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an instance of the LoanService
        $this->loanService = new LoanService();
    }

    public function testCreateLoan()
    {
        // Set up test data
        $user = User::factory()->create();
        $amount = 1000;
        $termDuration = 30;
        $loanStatus = LoanStatuses::factory()->create();
        // Call the createLoan method of the LoanService
        $loan = $this->loanService->createLoan($user->id, $amount, $termDuration);

        // Perform assertions on the created loan
        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals($user->id, $loan->user_id);
        $this->assertEquals($amount, $loan->amount);
        $this->assertEquals($termDuration, $loan->term_duration);
        $this->assertEquals(LoanStatuses::PENDING, $loan->status_id);
    }

    public function testGetLoansForUser()
    {
        // Create a test user
        $user = User::factory()->create();

        // Create some loans for the user
        $loanStatus = LoanStatuses::factory()->create();
        $loans = Loan::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // Call the getLoansForUser method of the LoanService
        $result = $this->loanService->getLoansForUser($user->id);

        // Perform assertions on the returned loans
        $this->assertCount(3, $result);
        $this->assertEquals($loans->pluck('id')->toArray(), $result->pluck('id')->toArray());
    }

    public function testGetLoanByIdWithValidLoanAndUser()
    {
        // Create a test user
        $user = User::factory()->create();

        // Create a test loan for the user
        $loanStatus = LoanStatuses::factory()->create();

        $loan = Loan::factory()->create([
            'user_id' => $user->id,
        ]);

        // Call the getLoanById method of the LoanService
        $result = $this->loanService->getLoanById($loan->id, $user->id);

        // Perform assertions on the returned loan
        $this->assertInstanceOf(Loan::class, $result);
        $this->assertEquals($loan->id, $result->id);
    }

    public function testGetLoanByIdWithInvalidLoanOrUser()
    {
        // Create a test user
        $user = User::factory()->create();

        // Create a test loan for another user
        $loanStatus = LoanStatuses::factory()->create();
        $loan = Loan::factory()->create();

        // Expect a null result when retrieving loan with invalid loan or user
        $this->assertNull($this->loanService->getLoanById($loan->id, $user->id));
        $this->assertNull($this->loanService->getLoanById($loan->id, $user->id));
    }

    public function testApproveLoanWithValidLoan()
    {
        // Create a test loan with pending status
        $loanStatus = LoanStatuses::factory()->create();

        $loan = Loan::factory()->create([
            'status_id' => LoanStatuses::PENDING,
        ]);

        // Call the approveLoan method of the LoanService
        $result = $this->loanService->approveLoan($loan->id);

        // Perform assertions on the approved loan
        $this->assertInstanceOf(Loan::class, $result);
        $this->assertEquals(LoanStatuses::APPROVED, $result->status_id);
    }
}
