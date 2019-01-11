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

use OxidProfessionalServices\GraphQl\Core\Types;
use OxidProfessionalServices\GraphQl\Core\AppContext;
use OxidProfessionalServices\GraphQl\Model\DataSource;
use OxidProfessionalServices\GraphQl\Model\User;

use GraphQL\Type\Definition\ObjectType as GraphQLType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class MutationRoot
 * The MutationRoot is the primary entry point for Mutations in the GraphQL Schema
 */
class MutationRoot extends GraphQLType
{
    /**
     * Type name
     *
     * @var string $type_name
     */
    private $typeName = 'Mutation';

    /**
     * Holds the $fields definition
     *
     * @var $fields
     */
    private $fields;

    /**
     * MutationRoot constructor
     */
    public function __construct()
    {
        /**
         * Configure Mutation Type
         */
        $config = [
            'name'          => $this->typeName,
            'description'   => 'Primary entry point for Mutations in the GraphQL Schema',
            'fields'        => $this->fields(),
            'resolveField'  => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];

        /**
         * Pass the config to the parent construct
         */
        parent::__construct($config);
    }

    /**
     * Setup data fields
     */
    public function fields()
    {
        if ( null === $this->fields ) {
            $fields             = [
                'login' => [
                    'type' => Types::login(),
                    'description' => 'Sign in with a registered user',
                    'args' => [
                        'username' => Types::nonNull(Types::string()),
                        'password' => Types::nonNull(Types::string()),
                    ],
                ],
                'createUser' => [
                    'type' => Types::createUser(),
                    'description' => 'Sign up a new user',
                    'args' => [
                        'name' => Types::nonNull(Types::string()),
                        'email' => Types::nonNull(Types::string()),
                        'password' => Types::nonNull(Types::string()),
                        'repeatPassword' => Types::nonNull(Types::string()),
                        'billingAdress' => Types::nonNull(Types::string()),
                        'deliveryAddress' => Types::nonNull(Types::string()),
                    ],
                ],
            ];
        }

        /**
         * Sort the fields alphabetically by keys
         * (this makes the schema documentation much nicer to browse)
         */
        ksort( $fields );

        return $fields;

    }

    public function login($rootValue, $args, $context)
    {
        $oUser = oxNew(User::class);
        return $oUser->login($args['username'], $args['password']);
    }

    public function createUser($rootValue, $args, $context)
    {
        // collecting values to check
        $sUser = $args['email'];
        $sEmail = $args['email'];
        $sPassword = $args['password'];
        $sPassword2 = $args['repeatPassword'];

        $aBillAdress = [
            "street" => $args['billingAdress']
        ];
        $aDelAdress = [
            "street" => $args['deliveryAddress']
        ];

        $oUser = oxNew(User::class);
        return $oUser->createUser($sUser, $sEmail, $sPassword, $sPassword2, $aBillAdress, $aDelAdress);
    }
}