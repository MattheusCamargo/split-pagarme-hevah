<?php

use Pagarme\Core\Kernel\Abstractions\AbstractModuleCoreSetup as MPSetup;
use Pagarme\Core\Kernel\ValueObjects\Id\OrderId;
use Woocommerce\Pagarme\Split\Helper\RecipientHelper;
use Woocommerce\Pagarme\Split\Service\SplitService;

add_filter('pagarme_split_order', 'calcular_split_order', 10, 2);

function calcular_split_order($order, $paymentMethod) {
    $orderId = $order->get_id();
    $splitService = new SplitService();
    $moneyService = new MoneyService(); // Crie uma instância do MoneyService
    
    // Obtém as porcentagens configuradas
    $affiliatePercentage = MPSetup::getModuleConfiguration()->getAffiliatePercentage();
    $vendorPercentage = MPSetup::getModuleConfiguration()->getVendorPercentage();

    // 1. Obter os IDs dos vendors e o valor total de cada um
    $vendors = [];
    foreach ($order->get_items() as $item) {
        $vendorId = get_post_field(' _dokan_vendor_id', $item->get_product()->get_id());
        if (!isset($vendors[$vendorId])) {
            $vendors[$vendorId] = 0;
        }
        $vendors[$vendorId] += floatval($item->get_total());
    }

    // 2. Obter o ID do afiliado
    global $wpdb;
    $affiliateId = $wpdb->get_var($wpdb->prepare(
        "SELECT affiliate_id FROM {$wpdb->prefix}affiliate_wp_referrals WHERE reference = %s",
        $orderId
    ));


    // 3. Calcular os valores do split
    $sellers = [];
    $totalAmount = $moneyService->floatToCents(floatval($order->get_total()));
    $marketplaceAmount = 0;


    if ($affiliateId) {
        $affiliateRecipientId = $splitService->getRecipientId($affiliateId); // Use SplitService para obter recipientId
        if ($affiliateRecipientId) {
            $affiliateAmount = ($affiliatePercentage / 100) * $totalAmount;
            $sellers[] = [
                'recipient_id' => $affiliateRecipientId->getValue(), // Corrigido: getValue()
                'amount' => intval($affiliateAmount),
                'options' => [
                    'charge_processing_fee' => false,
                    'charge_remainder_fee' => false,
                    'liable' => false,
                ],
            ];
        } else {
            error_log("Recipient ID not found for affiliate $affiliateId");
            // Trate o erro adequadamente, talvez retorne null ou lance uma exceção
            return null;
        }
    

        foreach ($vendors as $vendorId => $vendorTotal) {
            $vendorRecipientId = $splitService->getRecipientId($vendorId); // Use SplitService para obter recipientId
            if ($vendorRecipientId) {
                $vendorAmount = ($vendorPercentage / 100) * $moneyService->floatToCents($vendorTotal);

                $sellers[] = [
                    'recipient_id' => $vendorRecipientId->getValue(), // Corrigido: getValue()
                    'amount' => intval($vendorAmount),
                    'options' => [
                        'charge_processing_fee' => true,
                        'charge_remainder_fee' => true,
                        'liable' => true,
                    ],
                ];
            }  else {
            error_log("Recipient ID not found for vendor $vendorId");
            // Trate o erro adequadamente, talvez retorne null ou lance uma exceção
                return null;
        }
    }

}

    // Calcula o valor do marketplace (valor restante)
    $totalSplitAmount = 0;
    foreach ($sellers as $seller) {
        $totalSplitAmount += $seller['amount'];
    }

    $marketplaceAmount = $totalAmount - $totalSplitAmount;


    try {
        $response = $splitService->createSplit(new OrderId($order->get_id()), $sellers);

        // Tratar a resposta da API
        if (isset($response['errors'])) {
            error_log('Erro ao criar split: ' . print_r($response['errors'], true));
            return null; // Ou lance uma exceção
        }

    } catch (\Exception $e) {
        error_log('Erro ao criar split: ' . $e->getMessage());
        return null; // Ou lance uma exceção
    }

    return [
        'marketplace' => [
            'amount' => intval($marketplaceAmount)
        ],
        'sellers' => $sellers,
    ];
}