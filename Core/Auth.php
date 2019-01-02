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

use Exception;
use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\GraphQl\Core\Config;
use \Firebase\JWT\JWT;


/**
 * Class GraphQL Authorization
 *
 */
class Auth
{
    /**
     * GraphQL Module Config
     *
     * @var \OxidProfessionalServices\GraphQL\Core\Config
     */
    protected $_oGraphQLConfig;

    /**
     * Get access token from header and authorize
     *
     * @return string
     */
    public function authorize() {
        $authHeader = $this->getAuthorizationHeader();

        /*
        * Look for the 'authorization' header
        */
        if ($authHeader) {
            /*
            * Extract the jwt from the Bearer
            */
            list($jwt) = sscanf( $authHeader, 'Bearer %s');

            if ($jwt) {
                try {
                    /*
                    * return protected asset
                    */
                    return $this->authorizationCheck($jwt);

                } catch(Exception $e) {
                    /*
                    * the token was not able to be decoded.
                    * this is likely because the signature was not able to be verified (tampered token)
                    */
                header('HTTP/1.0 401 Unauthorized');
                throw new Exception('Unauthorized');

                }
            } else {
                /*
                * No token was able to be extracted from the authorization header
                */
                header('HTTP/1.0 400 Bad Request');
                throw new Exception('Bad Request');

            }
        } else {
            /*
            * The request lacks the authorization token
            */
            header('HTTP/1.0 400 Bad Request');
            throw new Exception('Token not found in request');
        }

    }

    /**
     *  Get header Authorization
     *
     * @return $aHeaders array
     */
    public function getAuthorizationHeader(){

        $authHeader = null;

        if (isset($_SERVER['Authorization'])) {
            $authHeader = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            //Nginx or fast CGI
            $authHeader = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $aRequestHeaders = apache_request_headers();
            // Server-side fix
            //(a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $aRequestHeaders = array_combine(array_map('ucwords', array_keys($aRequestHeaders)), array_values($aRequestHeaders));

            if (isset($aRequestHeaders['Authorization'])) {
                $authHeader = trim($aRequestHeaders['Authorization']);
            }
        }

        return $authHeader;
    }

    /**
     * Check if the reques ist autorized
     *
     * @var $sToken
     */
    public function authorizationCheck($sToken)
    {
        $sApiSecret = $this->getConfig()->getApiSecret();

        /*
        * decode the jwt using the apisecret from config
        */
        $aDecodedJWT = JWT::decode($sToken, $sApiSecret, array('HS512'));

        return $aDecodedJWT;
    }

    /**
     * Rerturn the GraphQL Module settings
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Config
     */
    public function getConfig()
    {
        if (is_null($this->_oGraphQLConfig)) {
            $this->_oGraphQLConfig = oxNew(Config::class);
        }

        return $this->_oGraphQLConfig;
    }

}