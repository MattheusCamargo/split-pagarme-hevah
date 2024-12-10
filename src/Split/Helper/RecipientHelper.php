<?php

namespace Woocommerce\Pagarme\Split\Helper;

class RecipientHelper
{
    public static function getRecipientId($userId)
    {
        return get_user_meta($userId, 'recipient_id', true);
    }

    public static function setRecipientId($userId, $recipientId)
    {
        return update_user_meta($userId, 'recipient_id', $recipientId);
    }

    public static function hasRecipientId($userId): bool
    {
        $recipient = self::getRecipientId($userId);
        return !empty($recipient);
    }

    public static function getAffiliateSplitPercentage(): float
    {
        return (float) MPSetup::getModuleConfiguration()->getAffiliatePercentage(); // Lê da configuração
    }
    
    public static function getVendorSplitPercentage(): float
    {
        return (float) MPSetup::getModuleConfiguration()->getVendorPercentage(); // Lê da configuração
    }


    public static function setUserSplitPercentage($userId, $percentage)
    {
        update_user_meta($userId, 'split_percentage', $percentage);
    }
}