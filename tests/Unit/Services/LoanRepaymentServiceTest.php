<?php

namespace Tests\Unit\Services;

use App\Http\Controllers\LoanRepaymentController;
use App\Models\Loan;
use App\Models\User;
use App\Models\LoanRepayment;
use App\Models\LoanStatuses;
use App\Services\LoanRepaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class LoanRepaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->loanRepaymentService = new LoanRepaymentService();
    }

    public function testCreateRepayment()
    {
        $loanId = 1;
        $amount = 2000;
        $user = User::factory()->create();

        $loanStatus = LoanStatuses::factory()->create(["id" => LoanStatuses::APPROVED]);
        // Create a loan
        $loan = Loan::factory()->create([
            'id' => $loanId,
            'user_id' => $user->id,
            'status_id' => LoanStatuses::APPROVED,
            'amount' => 4000,
            'term_duration' => 2
        ]);

        // Mock the LoanRepaymentService
        $repayment = $this->loanRepaymentService->createRepayment($loanId, $amount, $user->id);

        // Assertions
        $this->assertInstanceOf(LoanRepayment::class, $repayment);
        $this->assertEquals($amount, $repayment->amount);
        $this->assertEquals($loanId, $repayment->loan_id);
        $this->assertTrue($loan->repayments->contains($repayment));
        $this->assertEquals(LoanStatuses::APPROVED, $loan->status_id);
    }
}
