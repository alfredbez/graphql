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
use OxidProfessionalServices\GraphQl\Model\Category;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class GraphQL CategoryType.
 */
class CategoryType extends ObjectType
{
    /**
     * CategoryType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => 'Category',
            'description' => 'OXID eShop categories',
            'fields' => function () {
                return [
                    'id' => Types::id(),
                    'title' => Types::string(),
                    'parent' => Types::category(),
                ];
            },
            'interfaces' => [
                Types::node(),
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

    /**
     * Resolve parent category.
     *
     * @param $category
     */
    public function resolveParent($category)
    {
        if ($category['parent'] && $category['parent'] !== 'oxrootid') {
            $oCategory = oxNew(Category::class);

            return $oCategory->findCategory($category['parent']);
        }

        return null;
    }
}
