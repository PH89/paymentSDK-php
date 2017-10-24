<?php
/**
 * Shop System Plugins - Terms of Use
 *
 * The plugins offered are provided free of charge by Wirecard Central Eastern Europe GmbH
 * (abbreviated to Wirecard CEE) and are explicitly not part of the Wirecard CEE range of
 * products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License Version 3 (GPLv3) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Wirecard CEE does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the plugins at their own risk. Wirecard CEE does not guarantee their full
 * functionality neither does Wirecard CEE assume liability for any disadvantages related to
 * the use of the plugins. Additionally, Wirecard CEE does not guarantee the full functionality
 * for customized shop systems or installed plugins of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the plugin's functionality before starting productive
 * operation.
 *
 * By installing the plugin into the shop system the customer agrees to these terms of use.
 * Please do not use the plugin if you do not agree to these terms of use!
 */

namespace WirecardTest\PaymentSdk\Config;

use Wirecard\PaymentSdk\Config\ApplePayConfig;
use Wirecard\PaymentSdk\Entity\Amount;

/**
 * Class ConfigUTest
 * @package WirecardTest\PaymentSdk\Config
 * @method getVersionFromFile($file)
 */
class ApplePayConfigUTest extends \PHPUnit_Framework_TestCase
{
    const MAID = 'maiiiidddd';
    const SECRET = 'mytopsecretsecret';
    /**
     * @var ApplePayConfig
     */
    private $config;

    /**
     * @var Amount
     */

    public function setUp()
    {
        $this->config = new ApplePayConfig(self::MAID, self::SECRET);
        $this->amount = new Amount(10.0, 'EUR');
    }

    public function testAddSupportedNetworksViaString()
    {
        $this->config->addSupportedNetworks('amex, discover, masterCard, visa');
        $this->assertEquals('["amex","discover","masterCard","visa"]', $this->config->getSupportedNetworks());
    }

    public function testAddSupportedNetworksViaArray()
    {
        $this->config->addSupportedNetworks(array('amex', 'discover', 'masterCard', 'visa'));
        $this->assertEquals('["amex","discover","masterCard","visa"]', $this->config->getSupportedNetworks());
    }

    public function testSupportedNetworksOneByOne()
    {
        $this->config->addSupportedNetworks("amex");
        $this->config->addSupportedNetworks("discover");
        $this->config->addSupportedNetworks("masterCard");
        $this->config->addSupportedNetworks(" visa "); // whitespaces work as well

        $this->assertEquals('["amex","discover","masterCard","visa"]', $this->config->getSupportedNetworks());
    }

}