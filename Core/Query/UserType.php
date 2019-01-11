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

namespace OxidProfessionalServices\GraphQl\Core\Query;

use OxidProfessionalServices\GraphQl\Core\Types;
use OxidProfessionalServices\GraphQl\Model\User;

use GraphQL\Type\Definition\ObjectType as GraphQLType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class GraphQL UserType.
 */
class UserType extends GraphQLType
{
    /**
    * Type name.
    *
    * @var string
    */
    private $typeName = 'User';

    /**
     * UserType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => $this->typeName,
            'description' => 'OXID eShop users',
            'fields' => function () {
                return [
                    'id' => Types::id(),
                    'username' => Types::string(),
                    'email' => Types::email(),
                    'number' => Types::int(),
                    'firstName' => Types::string(),
                    'lastName' => Types::string(),
                    'token' => Types::string()
                ];
            },
            'interfaces' => [
                Types::node()
            ],
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                $method = 'resolve'.ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return $value[$info->fieldName];
                }
            },
        ];

        parent::__construct($config);
    }

}
