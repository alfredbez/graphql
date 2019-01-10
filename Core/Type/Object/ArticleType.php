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

namespace OxidProfessionalServices\GraphQl\Core\Type\Object;

use OxidProfessionalServices\GraphQl\Core\Types;
use OxidProfessionalServices\GraphQl\Model\Article;
use OxidProfessionalServices\GraphQl\Model\Category;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class GraphQL ArticleType.
 */
class ArticleType extends ObjectType
{
    /**
     * Type name.
     *
     * @var string
     */
    private $typeName = 'Article';

    /**
     * ArticleType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => $this->typeName,
            'description' => 'OXID eShop categories',
            'fields' => function () {
                return [
                    'id' => Types::id(),
                    'sku' => Types::string(),
                    'title' => Types::string(),
                    'description' => Types::string(),
                    'price' => Types::string(),
                    'image' => Types::string(),
                    'thumb' => Types::string(),
                    'icon' => Types::string(),
                    'category' => Types::category(), //TODO
                    'parent' => Types::article(),
                ];
            },
            'interfaces' => [
                Types::node(),
            ],
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                if($context->viewer){
                    $method = 'resolve'.ucfirst($info->fieldName);
                    if (method_exists($this, $method)) {
                        return $this->{$method}($value, $args, $context, $info);
                    } else {
                        return $value[$info->fieldName];
                    }
                }
            },
        ];

        parent::__construct($config);
    }

    /**
     * Resolve category article.
     *
     * @param $article
     */
    public function resolveCategory($article)
    {
        if ($article['category']) {
            $oCategory = oxNew(Category::class);

            return $oCategory->findCategory($article['category']);
        }

        return null;
    }

    /**
     * Resolve parent article.
     *
     * @param $article
     */
    public function resolveParent($article)
    {
        if ($article['parent']) {
            $oArticle = oxNew(Article::class);

            return $oArticle->findArticle($article['parent']);
        }

        return null;
    }
}
