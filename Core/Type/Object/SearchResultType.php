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
use OxidProfessionalServices\GraphQl\Model\User;
use OxidProfessionalServices\GraphQl\Model\Category;

use GraphQL\Type\Definition\UnionType;
/**
 * Class SearchResultType
 *
 */
class SearchResultType extends UnionType
{
        /**
    * Type name.
    *
    * @var string
    */
    private $typeName = 'SearchResult';

    /**
     * SearchResultType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => $this->typeName,
            'types' => function() {
                return [
                    Types::category(),
                    Types::user()
                ];
            },
            'resolveType' => function($value) {
                if ($value instanceof Category) {
                    return Types::category();
                } else if ($value instanceof User) {
                    return Types::user();
                }
            }
        ];

        parent::__construct($config);
    }

}