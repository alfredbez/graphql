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
use OxidEsales\Eshop\Core\Module\ModuleVariablesLocator;

/**
 *
 */
class GraphQLConfig extends \OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration
{

    /**
     * Metada settings variables
     *
     * @var array
     */
    protected $_aMetadataConfVars;

    /**
     * Render module settings tab
     * and add actions
     *
     * @return string
     */
    public function render()
    {
        $sRet = parent::render();
        $this->_aViewData["confactions"] = $this->_aMetadataConfVars['actions'];

        return $sRet;
    }

    /**
     * Generate a GraphQL ApiKey,
     * This a unique string, could be used to validate a token
     *
     */
    public function generateApiKey()
    {
        $sToken = base64_encode(openssl_random_pseudo_bytes(16));
        $_POST['confstrs']['strGraphQLApiKey'] = $sToken;
        $this->saveConfVars();
    }

    /**
     * Generate a GraphQL extract ApiSecret,
     * keep it secure! You'll need the exact key to verify the
     * token later.
     */
    public function generateApiSecret()
    {
        $sToken = base64_encode(openssl_random_pseudo_bytes(64));
        $_POST['confstrs']['strGraphQLApiSecret'] = $sToken;
        $this->saveConfVars();
    }

    /**
     * Load metada setting variables
     * and add config actions
     *
     * @param [type] $aModuleSettings
     * @return void
     */
    public function _loadMetadataConfVars($aModuleSettings)
    {
        $this->_aMetadataConfVars = parent::_loadMetadataConfVars($aModuleSettings);

        foreach ($aModuleSettings as $aValue) {
            $sName = $aValue["name"];
            $sAction = $aValue["action"];
            $this->_aMetadataConfVars['actions'][$sName] = $sAction;
        }

        return $this->_aMetadataConfVars;
    }

}