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

use OxidProfessionalServices\GraphQl\Model\User;


/**
 * Class AppContext
 * Instance available in all GraphQL resolvers as 3rd argument
 *
 */
class AppContext
{
    /**
     * Stores the url string for the current shop
     *
     * @var string $root_url
     */
    public $root_url;

    /**
     * Stores the active user object of the current user
     *
     * @var \OxidEsales\Eshop\Application\Model\User $viewer
     */
    public $viewer;

    /**
     * Stores everything from the $_REQUEST global
     *
     * @var \mixed $request
     */
    public $request;
}