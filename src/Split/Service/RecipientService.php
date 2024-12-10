<?php

namespace Woocommerce\Pagarme\Split\Service;


use Woocommerce\Pagarme\Split\Helper\RecipientHelper;
use Pagarme\Core\Kernel\Abstractions\AbstractModuleCoreSetup as MPSetup;
use Pagarme\Core\Kernel\Services\APIService;


class RecipientService
{
    public function createRecipient($data)
    {
        // 1. Valida os dados do formulário

        // 2. Cria o recipient na API do Pagar.me
        $apiService = new APIService();
        $recipientData = $this->buildRecipientData($data);

        try {
            $response = $apiService->createRecipient($recipientData);

            // 3. Salva o recipient_id na user meta
            if (isset($response['id'])) {
                RecipientHelper::setRecipientId(get_current_user_id(), $response['id']);
                RecipientHelper::setUserSplitPercentage(get_current_user_id(), $data['split_percentage']); // Salva a porcentagem
                return $response; // Retorna a resposta da API
            } else {
                throw new \Exception('Erro ao criar o recebedor na Pagar.me.');
            }

        } catch (\Exception $e) {
            // Trate a exceção (log, notificação, etc.)
            throw $e;
        }


    }

    private function buildRecipientData(array $data)
    {
        $recipientData = [];
    
        if ($data['recipient_type'] === 'pf') {
            $recipientData['register_information'] = [
                'name' => $data['name'],
                'email' => $data['email'], // Adicione o campo de email
                'document' => $data['document'],
                'type' => 'individual',
                // ... outros campos para PF ... (birthdate, mother_name, etc.)
                'phone_numbers' => [
                    [
                      'ddd' => $data['ddd'], // ddd do telefone
                      'number' => $data['number'], // numero do telefone
                      'type' => 'mobile' // tipo de telefone
                    ]
                ],
                 'address' => [
                    'street' => $data['street'],
                    'complementary' => $data['complementary'],
                    'street_number' => $data['street_number'],
                    'neighborhood' => $data['neighborhood'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip_code' => $data['zip_code'],
                    'reference_point' => $data['reference_point'],
                ],
            ];
        } elseif ($data['recipient_type'] === 'pj') {
            $recipientData['register_information'] = [
                'company_name' => $data['company_name'], // company_name
                'trading_name' => $data['trading_name'],
                'email' => $data['email'], // Adicione o campo de email
                'document' => $data['document'],
                'type' => 'corporation',
                'site_url' => $data['site_url'], // site_url
                'annual_revenue' => $data['annual_revenue'], // annual_revenue
                'corporation_type' => $data['corporation_type'], // corporation_type
                'founding_date' => $data['founding_date'], // founding_date
                'main_address' => [
                    'street' => $data['street'],
                    'complementary' => $data['complementary'],
                    'street_number' => $data['street_number'],
                    'neighborhood' => $data['neighborhood'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip_code' => $data['zip_code'],
                    'reference_point' => $data['reference_point'],
                ],
                // ... outros campos para PJ ... (managing_partners, etc.)
                 'phone_numbers' => [
                    [
                        'ddd' => $data['ddd'], // ddd do telefone
                        'number' => $data['number'], // numero do telefone
                        'type' => 'mobile' // tipo de telefone
                    ]
                ],
    
            ];
        }
    
    
         // Dados Bancários (comuns para PF e PJ)
        $recipientData['default_bank_account'] = [
            'holder_name' => $data['holder_name'],
            'holder_type' => $data['holder_type'],
            'holder_document' => $data['holder_document'],
            'bank' => $data['bank'],
            'branch_number' => $data['branch_number'],
            'branch_check_digit' => $data['branch_check_digit'],
            'account_number' => $data['account_number'],
            'account_check_digit' => $data['account_check_digit'],
            'type' => $data['account_type'],
    
        ];
    
        // Configurações de Transferência (comuns para PF e PJ)
        $recipientData['transfer_settings'] = [
            'transfer_enabled' => true, // ou $data['transfer_enabled'] se for um campo do formulário
            'transfer_interval' => 'Daily', // ou outro intervalo
            'transfer_day' => 0, // ou outro dia
        ];
    
        // Configurações de Antecipação Automática (comuns para PF e PJ)
        $recipientData['automatic_anticipation_settings'] = [
            'enabled' => false, // ou $data['automatic_anticipation_enabled']
            'type' => 'full', // ou outro tipo
            'volume_percentage' => '50', // ou outra porcentagem
            'delay' => null,
        ];
    
        // Código do Recipient (opcional)
        if (isset($data['code'])) {
            $recipientData['code'] = $data['code'];
        }
    
    
        return $recipientData;
    }
}