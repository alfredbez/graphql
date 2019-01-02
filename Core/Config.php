<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @category    module
 * @package     GraphQL
 * @link        http://www.oxid-esales.com
 * @copyright   (C) OXID eSales AG 2003-2018
 * @version     OXID eSales GraphQL
 */

namespace OxidProfessionalServices\GraphQl\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class defines module Config on Shop.
 */
class Config extends \OxidEsales\Eshop\Core\Config
{
    /**
     * GraphQL Apikey.
     *
     * @var string
     */
    protected $_sApiKey = null;

    /**
     * GraphQL ApiSecret
     *
     * @var string
     */
    protected $_sApiSecret = null;

    /**
     * GraphQl ApiKey setter
     *
     * @param string $sApiKey
     * @return void
     */
    public function setApiKey($sApiKey)
    {
        $this->_sApiKey = $sApiKey;
    }

    /**
     * GraphQl ApiKey getter
     *
     * @return string
     */
    public function getApiKey()
    {
        $sApiKey = $this->getConfig()->getConfigParam('strGraphQLApiKey');

        if ($sApiKey) {
            $this->setApiKey($sApiKey);
        }
        return $this->_sApiKey;
    }

        /**
     * GraphQl ApiSecret setter
     *
     * @param string $sApiKey
     * @return void
     */
    public function setApiSecret($sApiKey)
    {
        $this->_sApiSecret = $sApiKey;
    }

    /**
     * GraphQl ApiSecret getter
     *
     * @return string
     */
    public function getApiSecret()
    {
        $sApiSecret = $this->getConfig()->getConfigParam('strGraphQLApiSecret');

        if ($sApiSecret) {
            $this->setApiSecret($sApiSecret);
        }
        return $this->_sApiSecret;
    }

    /**
     * Returns oxConfig instance
     *
     * @return \OxidEsales\Eshop\Core\Config
     */
    public function getConfig()
    {
        return Registry::getConfig();
    }

}