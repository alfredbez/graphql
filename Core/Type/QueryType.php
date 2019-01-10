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
use OxidProfessionalServices\GraphQl\Model\Action;
use OxidProfessionalServices\GraphQl\Model\Article;
use OxidProfessionalServices\GraphQl\Model\Category;
use OxidProfessionalServices\GraphQl\Model\User;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 * Class QueryType
 * The QueryType is the primary entry for Queries in the GraphQL Schema
 *

 */
class QueryType extends ObjectType
{
    /**
     * Type name.
     *
     * @var string
     */
    private $typeName = 'Query';

    /**
     * QueryType constructor
     */
    public function __construct()
    {
        /**
         * Configure Query Type
         */
        $config = [
            'name' => $this->typeName,
            'description' => 'Primary entry for Queries in the GraphQL Schema.',
            'fields' => $this->fields(),
            'resolveField' => function ($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            },
        ];

        /*
         * Pass the config to the parent construct
         */
        parent::__construct($config);
    }

    /**
     * Setup data fields
     */
    public function fields()
    {
        $fields = [
            'action' => [
                'type' => Types::action(),
                'description' => 'Returns an action by id',
                'args' => [
                    'id' => Types::nonNull(Types::id()),
                ],
            ],
            'actions' => [
                'type' => Types::listOf(Types::action()),
                'description' => 'Returns list of actions',
                'args' => [
                    'limit' => [
                        'type' => Types::int(),
                        'description' => 'Number of actions to be returned',
                        'defaultValue' => 20,
                    ],
                    'group' => [
                        'type' => Types::int(),
                        'description' => 'Type of the action to be returned',
                        'defaultValue' => 3,
                    ],
                ],
            ],
            'article' => [
                'type' => Types::article(),
                'description' => 'Returns an article by id',
                'args' => [
                    'id' => Types::nonNull(Types::id()),
                ],
            ],
            'articles' => [
                'type' => Types::listOf(Types::article()),
                'description' => 'Returns list of articles',
                'args' => [
                    'limit' => [
                        'type' => Types::int(),
                        'description' => 'Number of articles to be returned',
                        'defaultValue' => 20,
                    ],
                ],
            ],
            'category' => [
                'type' => Types::category(),
                'description' => 'Returns a category by id',
                'args' => [
                    'id' => Types::nonNull(Types::id()),
                ],
            ],
            'categories' => [
                'type' => Types::listOf(Types::category()),
                'description' => 'Returns list of categories',
                'args' => [
                    'limit' => [
                        'type' => Types::int(),
                        'description' => 'Number of categories to be returned',
                        'defaultValue' => null,
                    ],
                ],
            ],
            'user' => [
                'type' => Types::user(),
                'description' => 'Returns user by id',
                'args' => [
                    'id' => Types::nonNull(Types::id())
                ]
            ],
            'viewer' => [
                'type' => Types::user(),
                'description' => 'Represents currently logged-in user'
            ],
            'deprecatedField' => [
                'type' => Types::string(),
                'deprecationReason' => 'This field is deprecated!'
            ],
            'fieldWithException' => [
                'type' => Types::string(),
                'resolve' => function() {
                    throw new \Exception("Exception message thrown in field resolver");
                }
            ],
            'welcome' => Type::string()
        ];

        /*
         * Sort the fields alphabetically by keys
         * (this makes the schema documentation much nicer to browse)
         */
        ksort($fields);

        return $fields;
    }

    public function action($rootValue, $args)
    {
        $oAction = oxNew(Action::class);
        return $oAction->findAction($args['id']);
    }

    public function actions($rootValue, $args)
    {
        $oAction = oxNew(Action::class);
        return $oAction->findActions($args['limit'], $args['group']);
    }

    public function article($rootValue, $args)
    {
        $oArticle = oxNew(Article::class);
        return $oArticle->findArticle($args['id']);
    }

    public function articles($rootValue, $args)
    {
        $oArticle = oxNew(Article::class);
        $args += ['after' => null];
        return $oArticle->findArticles($args['limit'], $args['after']);
    }

    public function category($rootValue, $args)
    {
        $oCategory = oxNew(Category::class);
        return $oCategory->findCategory($args['id']);
    }

    public function categories($rootValue, $args)
    {
        $oCategory = oxNew(Category::class);
        $args += ['after' => null];
        return $oCategory->findCategories($args['limit'], $args['after']);
    }

    public function user($rootValue, $args)
    {
        $oUser = oxNew(User::class);
        return $oUser->findUser($args['id']);
    }

    public function viewer($rootValue, $args, $context)
    {
        $oUser = oxNew(User::class);
        $sViewerId = $context->viewer;
        return $oUser->findUser($sViewerId);
    }

    public function deprecatedField()
    {
        return 'You can request deprecated field, but it is not displayed in auto-generated documentation by default.';
    }

    public function welcome()
    {
        return 'Your OXID GraphQL endpoint is ready! Login in the Admin site and use GraphiQL to browse API';
    }
}
