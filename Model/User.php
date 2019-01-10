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

namespace OxidProfessionalServices\GraphQl\Model;

use OxidProfessionalServices\GraphQl\Core\AppContext;
use OxidProfessionalServices\GraphQl\Core\Auth;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\DatabaseProvider;
use GraphQL\Error\Error;
use Exception;

class User extends BaseModel
{
    /**
     * Sign In
     *
     * @param string $sUser
     * @param string $sPass
     * @return array|null
     */
    public function login($sUser, $sPass)
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        try {
            $oUser->login($sUser, $sPass);
            $aUser =$this->authorizeUser($oUser);

            $oAppContext = oxNew(AppContext::class);

            return $aUser;

        } catch(\Exception $error) {
            header('HTTP/1.0 401 Unauthorized');
            throw new Error('Unauthorized');
        }
    }

    /**
     * Sign Up
     *
     * @param string $sUser
     * @param string $sPass
     * @return array|null
     */
    public function register($sUser, $sPass)
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        try {
            $oUser->login($sUser, $sPass);
            $aUser =$this->authorizeUser($oUser);

            $oAppContext = oxNew(AppContext::class);

            return $aUser;

        } catch(\Exception $error) {
            header('HTTP/1.0 401 Unauthorized');
            throw new Error('Unauthorized');
        }
    }

    /**
     *
     */
    private function authorizeUser($oUser){
        // create json web token
        $oAuth = oxNew(Auth::class);
        $sJwt = $oAuth->sign($oUser);

        $aUser = [
            'id' => $oUser->getId(),
            'username' => $oUser->oxuser__oxusername->value,
            'email' => $oUser->oxuser__oxusername->value,
            'number' => $oUser->oxuser__oxcustnr->value,
            'firstName' => $oUser->oxuser__oxfname->value,
            'lastName' => $oUser->oxuser__oxlname->value,
            'token' => $sJwt
        ];

        return $aUser;
    }

    /**
     * Find a user by id.
     * @param $id
     *
     * @return array|null
     */
    public function findUser($id)
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oUser->load($id);

        return $this->authorizeUser($oUser);
    }
}
