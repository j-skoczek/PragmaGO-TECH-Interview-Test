<?php

require __DIR__ . '/vendor/autoload.php';

use PragmaGoTech\Interview\Controller\FeeCalculator;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Repository\FeeRepository;
use PragmaGoTech\Interview\Service\FeeCalculator as FeeCalculatorService;
use PragmaGoTech\Interview\Service\Math;

$term = 12;
$amount = 2250.00;

$feeRepository = new FeeRepository();
$loanProposal = new LoanProposal($term, $amount);
$mathService = new Math();
$feeCalculatorService = new FeeCalculatorService($mathService);
$feeCalculator = new FeeCalculator($loanProposal, $feeRepository, $feeCalculatorService);

echo $feeCalculator->calculateFee();
echo "\n";
