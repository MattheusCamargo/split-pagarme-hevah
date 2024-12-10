<?php

namespace Woocommerce\Pagarme\Split\Service;

use Pagarme\Core\Kernel\Abstractions\AbstractModuleCoreSetup as MPSetup;
use Pagarme\Core\Kernel\Services\APIService;
use Pagarme\Core\Kernel\Services\MoneyService;
use Pagarme\Core\Kernel\ValueObjects\Id\OrderId;
use Woocommerce\Pagarme\Split\ValueObject\RecipientId;

class SplitService
{

    /** @var MoneyService moneyService */
    private $moneyService;

    public function __construct()
    {
        $this->moneyService = new MoneyService();
    }


    public function createSplit($orderId, $splitData)
    {

        // Realiza o split na API do Pagar.me
        $apiService = new APIService();
        $response = $apiService->createSplit($orderId, $splitData);

        return $response; // Retorna a resposta da API
    }


    private function calculateSplitAmount(int $totalAmount, float $percentage, int $precision = 100): int
    {
        return (int) round(($totalAmount * $percentage) / $precision, 0);
    }


    private function getRecipientId(int $userId): ?RecipientId
    {
        $recipientId = get_user_meta($userId, 'recipient_id', true);
        if ($recipientId) {
            return new RecipientId($recipientId);
        }
        return null;
    }

    private function getTransactionIdFromResponse($response) {

        if (isset($response['charges'][0]['transaction_id'])) {
            return $response['charges'][0]['transaction_id'];
        }
        if (isset($response['transactions'][0]['id'])) {
            return $response['transactions'][0]['id'];
        }

        return null;
    }


}