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

namespace OxidProfessionalServices\GraphQl\Controller\Admin;

use OxidProfessionalServices\GraphQl\Core\Auth;
use OxidEsales\Eshop\Core\Registry;
use \Firebase\JWT\JWT;

/**
 * Voyager Admin Tool
 */
class Voyager extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Template to render
     *
     * @var string
     */
    protected $_sThisTemplate = 'voyager.tpl';

    /**
     * Render
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        $oConfig = Registry::getConfig();
        $sJwt = $oConfig->getConfigParam('strGraphQLApiToken');

        $this->_aViewData["sBearer"] = $sJwt;

        return $this->_sThisTemplate;
    }

}