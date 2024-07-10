<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Controller;

use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Repository\FeeRepositoryInterface;
use PragmaGoTech\Interview\Service\FeeCalculatorInterface as FeeCalculatorServiceInterface;

class FeeCalculator implements FeeCalculatorInterface
{
    protected LoanProposal $loanProposal;
    private FeeRepositoryInterface $feeRepository;
    private FeeCalculatorServiceInterface $feeCalculatorService;

    public function __construct(
        LoanProposal $loanProposal,
        FeeRepositoryInterface $feeRepository,
        FeeCalculatorServiceInterface $feeCalculatorService
    ) {
        $this->loanProposal = $loanProposal;
        $this->feeRepository = $feeRepository;
        $this->feeCalculatorService = $feeCalculatorService;
    }

    /**
     * Calculate fee for given loan proposal
     *
     * @return float
     */
    public function calculateFee(): float
    {
        $feeRules = $this->feeRepository->getFeeRules($this->loanProposal->term());

        if (array_key_exists($this->loanProposal->centesimalAmount(), $feeRules)) {
            return ($feeRules[$this->loanProposal->centesimalAmount()])->fee();
        } else {
            return $this->feeCalculatorService->calculateFee($this->loanProposal, $feeRules);
        }
    }
}
