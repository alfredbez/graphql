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

namespace OxidProfessionalServices\GraphQl\Core\Type\Enum;

use GraphQL\Type\Definition\EnumType;

/**
 * Class ImageSizeEnumType
 *
 */
class ContentFormatEnumType extends EnumType
{
    const FORMAT_TEXT = 'TEXT';
    const FORMAT_HTML = 'HTML';

    /**
     * ContentFormatEnum constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => 'ContentFormatEnum',
            'values' => [self::FORMAT_TEXT, self::FORMAT_HTML]
        ];

        parent::__construct($config);
    }
}