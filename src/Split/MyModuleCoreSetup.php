<?php

namespace Woocommerce\Pagarme\Split;

use Pagarme\Core\Kernel\Abstractions\AbstractModuleCoreSetup as PagarmeAbstractModuleCoreSetup;


class MyModuleCoreSetup extends PagarmeAbstractModuleCoreSetup
{

    public function getModuleConfiguration()
    {
        $moduleConfig = parent::getModuleConfiguration();

        // Adicione os seus métodos customizados aqui:
        $moduleConfig->setAffiliatePercentage($this->moduleConfig->getData('affiliate_percentage'));
        $moduleConfig->setVendorPercentage($this->moduleConfig->getData('vendor_percentage'));
        return $moduleConfig;
    }


    public function getAffiliatePercentage(): float
    {
        return (float) $this->moduleConfig->getData('affiliate_percentage');
    }

    public function getVendorPercentage(): float
    {
        return (float) $this->moduleConfig->getData('vendor_percentage');
    }
}