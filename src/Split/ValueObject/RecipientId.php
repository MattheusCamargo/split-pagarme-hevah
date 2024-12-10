<?php

namespace Woocommerce\Pagarme\Split\ValueObject;

class RecipientId
{
    private $recipientId;

    public function __construct(string $recipientId)
    {
        // Validação se necessário (ex: formato do ID)
        $this->recipientId = $recipientId;
    }

    public function getValue(): string
    {
        return $this->recipientId;
    }
}