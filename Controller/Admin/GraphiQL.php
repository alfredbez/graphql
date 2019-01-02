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
use OxidEsales\Eshop\Core\Registry;
use \Firebase\JWT\JWT;

/**
 * GraphiQL Admin Tool
 */
class GraphiQL extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Template to render
     *
     * @var string
     */
    protected $_sThisTemplate = 'graphiql.tpl';

    /**
     * Render
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $oUser = $this->getUser();
        $aData = [
            'id' => $oUser->getId(),
            'username' => $oUser->oxuser__oxusername->value,
        ];

        $this->_aViewData["sBearer"] = $this->_auth($aData);

        return $this->_sThisTemplate;
    }

    /**
     * Get authorization token
     *
     * @param array $aData
     * @return string
     */
    protected function _auth($aData)
    {
        $oConfig = Registry::getConfig();

        $sTokenId = $oConfig->getConfigParam('strGraphQLApiKey');
        $dtIssuedAt = time();
        $dtNotBefore = $dtIssuedAt + 10; //Adding 10 seconds
        $dtExpire = strtotime('1 hour'); // Adding 1 year
        $sServerName = $oConfig->getShopUrl(); // Retrieve the server name from config file

        /*
        * Create the token as an array
        */
        $aToken = [
            'iat'  => $dtIssuedAt,          // Issued at: time when the token was generated
            'jti'  => $sTokenId,            // Json Token Id: an unique identifier for the token
            'iss'  => $sServerName,         // Issuer
            'aud'  => $sServerName,         // Issuer
            //'nbf'  => $dtNotBefore,         // Not before
            'exp'  => $dtExpire,            // Expire
            'data' => $aData,               // Data related to the signer user
        ];


        /*
        * Extract the key, which is coming from the config file.
        *
        * keep it secure! You'll need the exact key to verify the
        * token later.
        */
        $sSecretKey = $oConfig->getConfigParam('strGraphQLApiSecret');

        /*
        * Encode the array to a JWT string.
        * Second parameter is the key to encode the token.
        *
        * The output string can be validated at http://jwt.io/
        */
        $sJwt = JWT::encode(
            $aToken,      //Data to be encoded in the JWT
            $sSecretKey, // The signing key
            'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        return $sJwt;
    }
}