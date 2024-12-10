<?php

namespace Woocommerce\Pagarme\Split\Model;

use Woocommerce\Pagarme\Split\ValueObject\RecipientId;

class Split
{
    private $affiliateRecipientId;
    private $vendorRecipientId;
    private $marketplaceSplitAmount;
    private $affiliateSplitAmount;
    private $vendorSplitAmount;
    private $status;
    private $transactionId; // ID da transação Pagar.me

    public function __construct(
        ?RecipientId $affiliateRecipientId,
        RecipientId $vendorRecipientId,
        int $marketplaceSplitAmount,
        ?int $affiliateSplitAmount,
        int $vendorSplitAmount,
        string $transactionId
    ) {
        $this->affiliateRecipientId = $affiliateRecipientId;
        $this->vendorRecipientId = $vendorRecipientId;
        $this->marketplaceSplitAmount = $marketplaceSplitAmount;
        $this->affiliateSplitAmount = $affiliateSplitAmount;
        $this->vendorSplitAmount = $vendorSplitAmount;
        $this->transactionId = $transactionId;
        $this->status = 'pending'; // Inicialmente pendente
    }


    // Getters and Setters para todos os atributos


    public function getAffiliateRecipientId(): ?RecipientId
    {
        return $this->affiliateRecipientId;
    }

    public function getVendorRecipientId(): RecipientId
    {
        return $this->vendorRecipientId;
    }

    public function getMarketplaceSplitAmount(): int
    {
        return $this->marketplaceSplitAmount;
    }

    public function getAffiliateSplitAmount(): ?int
    {
        return $this->affiliateSplitAmount;
    }

    public function getVendorSplitAmount(): int
    {
        return $this->vendorSplitAmount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    // ... outros getters e setters ...
}