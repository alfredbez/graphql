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

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class RootMutationType
 * The RootMutationType is the primary entry point for Mutations in the GraphQL Schema
 */
class RootMutationType extends ObjectType
{
    /**
     * Type name
     *
     * @var string $type_name
     */
    private static $typeName = 'rootMutation';

    /**
     * Holds the $fields definition.
     *
     * @var $fields
     */
    private static $fields;

    /**
     * RootMutationType constructor.
     */
    public function __construct()
    {
        /**
         * Configure the RootMutation
         */
        $config = [
            'name'          => self::$typeName,
            'description'   => 'Root mutation',
            'fields'        => self::fields(),
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
     * Setup data
     */
    public static function fields()
    {
        if ( null === self::$fields ) {
            $fields             = [];
        }

        /**
         * Sort the fields alphabetically by keys
         * (this makes the schema documentation much nicer to browse)
         */
        ksort( $fields );

        return $fields;

    }
}