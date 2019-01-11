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

            // finalizing ...
            return $this->authorizeUser($oUser);

        } catch(\Exception $error) {
            header('HTTP/1.0 401 Unauthorized');
            throw new Error($error->getMessage());
        }
    }

    /**
     * Sign Up
     *
     * @return array|null
     */
    public function createUser($sUser, $sEmail, $sPassword, $sPassword2, $aBillAdress, $aDelAdress)
    {
        $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);

        try {
            //TODO
            //$oUser->checkValues($sUser, $sPassword, $sPassword2, $aBillAdress, $aDelAdress);

            $iActState = $blActiveLogin ? 0 : 1;

            // setting values
            $oUser->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field($sUser, \OxidEsales\Eshop\Core\Field::T_RAW);
            $oUser->setPassword($sPassword);
            $oUser->oxuser__oxactive = new \OxidEsales\Eshop\Core\Field($iActState, \OxidEsales\Eshop\Core\Field::T_RAW);

            // used for checking if user email currently subscribed
            $iSubscriptionStatus = $oUser->getNewsSubscription()->getOptInStatus();

            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $database->startTransaction();

            try {
                $oUser->createUser();
                //TODO
                //$oUser = $this->configureUserBeforeCreation($oUser);
                $oUser->load($oUser->getId());
                //$oUser->changeUserData($oUser->oxuser__oxusername->value, $sPassword, $sPassword, $aBillAdress, $aDelAdress);

                if ($blActiveLogin) {
                    // accepting terms...
                    $oUser->acceptTerms();
                }

                $database->commitTransaction();

            } catch (Exception $exception) {
                $database->rollbackTransaction();

                throw $exception;
            }

            $sUserId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable("su");
            $sRecEmail = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable("re");
            if ($this->getConfig()->getConfigParam('blInvitationsEnabled') && $sUserId && $sRecEmail) {
                // setting registration credit points..
                $oUser->setCreditPointsForRegistrant($sUserId, $sRecEmail);
            }

            // assigning to newsletter
            $blOptin = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('blnewssubscribed');
            if ($blOptin && $iSubscriptionStatus == 1) {
                // if user was assigned to newsletter
                // and is creating account with newsletter checked,
                // don't require confirm
                $oUser->getNewsSubscription()->setOptInStatus(1);
                $oUser->addToGroup('oxidnewsletter');
                $this->_blNewsSubscriptionStatus = 1;
            } else {
                $blOrderOptInEmailParam = $this->getConfig()->getConfigParam('blOrderOptInEmail');
                $this->_blNewsSubscriptionStatus = $oUser->setNewsSubscription($blOptin, $blOrderOptInEmailParam);
            }

            $oUser->addToGroup('oxidnotyetordered');
            return $this->authorizeUser($oUser);

        } catch(\Exception $error) {
            header('HTTP/1.0 401 Unauthorized');
            throw new Error($error->getMessage());
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
