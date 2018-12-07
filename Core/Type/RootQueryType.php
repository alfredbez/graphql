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
use OxidProfessionalServices\GraphQl\Model\Category;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class RootQueryType
 * The RootQueryType is the primary entry for Queries in the GraphQL Schema.
 */
class RootQueryType extends ObjectType
{
    /**
     * Type name
     *
     * @var string $type_name
     */
    private static $typeName = 'rootQuery';

    /**
     * RootQueryType constructor.
     */
    public function __construct()
    {
        /**
         * Configure the RootQuery
         */
        $config = [
            'name'          => self::$typeName,
            'description'   => 'Root query',
            'fields'        => self::fields(),
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
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
        $fields = [
            'category' => [
                'type' => Types::category(),
                'description' => 'Returns category by id',
                'args' => [
                    'id' => Types::nonNull(Types::id())
                ]
            ],
            'categories' => [
                'type' => Types::listOf(Types::category()),
                'description' => 'Returns list of categories',
                'args' => [
                    'limit' => [
                        'type' => Types::int(),
                        'description' => 'Number of categories to be returned',
                        'defaultValue' => 10
                    ]
                ]
            ],
            'welcome' => Types::string()
        ];

        /**
         * Sort the fields alphabetically by keys
         * (this makes the schema documentation much nicer to browse)
         */
        ksort( $fields );

        return $fields;

    }

    public function categories($rootValue, $args)
    {
        $oCategory = oxNew(Category::class);

        $args += ['after' => null];
        return $oCategory->findCategories($args['limit'], $args['after']);
    }

    public function welcome()
    {
        return 'Your OXID GraphQL endpoint is ready! Login in the Admin site and use GraphiQL to browse API';
    }

}