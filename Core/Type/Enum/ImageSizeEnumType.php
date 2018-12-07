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

use OxidProfessionalServices\GraphQl\Model\Image;

use GraphQL\Type\Definition\EnumType;

/**
 * Class ImageSizeEnumType
 *
 */
class ImageSizeEnumType extends EnumType
{
    /**
     * ImageSizeEnumType constructor.
     */
    public function __construct()
    {
        $config = [
                // Note: 'name' option is not needed in this form - it will be inferred from className
                'values' => [
                'icon' => Image::SIZE_ICON,
                'thumb' => Image::SIZE_THUMB,
                'small' => Image::SIZE_SMALL,
                'medium' => Image::SIZE_MEDIUM,
                'original' => Image::SIZE_ORIGINAL
            ]
        ];

        parent::__construct($config);
    }
}