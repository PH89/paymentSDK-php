<?php
/**
 * Shop System SDK - Terms of Use
 *
 * The SDK offered are provided free of charge by Wirecard AG and are explicitly not part
 * of the Wirecard AG range of products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License Version 3 (GPLv3) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Wirecard AG does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the SDK at their own risk. Wirecard AG does not guarantee their full
 * functionality neither does Wirecard AG assume liability for any disadvantages related to
 * the use of the SDK. Additionally, Wirecard AG does not guarantee the full functionality
 * for customized shop systems or installed SDK of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the SDK's functionality before starting productive
 * operation.
 *
 * By installing the SDK into the shop system the customer agrees to these terms of use.
 * Please do not use the SDK if you do not agree to these terms of use!
 */

namespace Wirecard\PaymentSdk\Transaction;

use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Exception\MandatoryFieldMissingException;

/**
 * Class PaylibTransaction
 *
 * WARNING: This payment method is still in development, please do not use it in its current state
 *
 * @package Wirecard\PaymentSdk\Transaction
 */
class PaylibTransaction extends Transaction
{
    const NAME = 'paylib';

    /**
     * @return array
     */
    protected function mappedSpecificProperties()
    {
        if (!$this->accountHolder instanceof AccountHolder && $this->operation === Operation::PAY) {
            throw new MandatoryFieldMissingException('No account holder set.');
        }

        return [];
    }

    /**
     * @return string
     */
    protected function retrieveTransactionTypeForPay()
    {
        return self::TYPE_DEBIT;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return self::ENDPOINT_PAYMENT_METHODS;
    }
}