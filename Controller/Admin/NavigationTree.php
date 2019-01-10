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

/**
 *
 */
class NavigationTree extends \OxidEsales\Eshop\Application\Controller\Admin\NavigationTree
{
    /**
     * Init function
     *
     * @return void
     */
    public function init()
    {
        $blGraphiQLTool = $oConfig->getConfigParam('blGraphiQLTool');
        $blVoyagerTool = $oConfig->getConfigParam('blVoyagerTool');

    }

    /**
     * Render module settings tab
     * and add actions
     *
     * @return string
     */
    public function render()
    {
        $sRet = parent::render();
        //TODO

        return $sRet;
    }

}