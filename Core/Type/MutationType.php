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

namespace OxidProfessionalServices\GraphQl\Core\Type;

use OxidProfessionalServices\GraphQl\Core\Types;
use OxidProfessionalServices\GraphQl\Core\AppContext;
use OxidProfessionalServices\GraphQl\Model\DataSource;
use OxidProfessionalServices\GraphQl\Model\User;


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class MutationType
 * The MutationType is the primary entry point for Mutations in the GraphQL Schema
 */
class MutationType extends ObjectType
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
     * MutationType constructor
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
            //TODO
            $fields             = [
                'login' => [
                    'type' => Types::login(),
                    'description' => 'Represents a logged-in user',
                    'args' => [
                        'username' => Types::nonNull(Types::string()),
                        'password' => Types::nonNull(Types::string()),
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
}