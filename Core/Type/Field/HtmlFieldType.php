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

namespace OxidProfessionalServices\GraphQl\Core\Type\Field;

use OxidProfessionalServices\GraphQl\Core\Type\Enum\ContentFormatEnumType;
use OxidProfessionalServices\GraphQl\Core\Types;

/**
 * Class HtmlField
 *
 */
class HtmlFieldType
{
    /**
     * @param $name
     * @param null $objectKey
     * @return array
     */
    public static function build($name, $objectKey = null)
    {
        $objectKey = $objectKey ?: $name;
        // Demonstrates how to organize re-usable fields
        // Usual example: when the same field with same args shows up in different types
        // (for example when it is a part of some interface)
        return [
            'name' => $name,
            'type' => Types::string(),
            'args' => [
                'format' => [
                    'type' => Types::contentFormatEnum(),
                    'defaultValue' => ContentFormatEnumType::FORMAT_HTML
                ],
                'maxLength' => Types::int()
            ],
            'resolve' => function($object, $args) use ($objectKey) {
                $html = $object->{$objectKey};
                $text = strip_tags($html);
                if (!empty($args['maxLength'])) {
                    $safeText = mb_substr($text, 0, $args['maxLength']);
                } else {
                    $safeText = $text;
                }
                switch ($args['format']) {
                    case ContentFormatEnumType::FORMAT_HTML:
                        if ($safeText !== $text) {
                            // Text was truncated, so just show what's safe:
                            return nl2br($safeText);
                        } else {
                            return $html;
                        }
                    case ContentFormatEnumType::FORMAT_TEXT:
                    default:
                        return $safeText;
                }
            }
        ];
    }
}