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

namespace OxidProfessionalServices\GraphQl\Core;

use OxidProfessionalServices\GraphQl\Core\Type\Enum\ContentFormatEnumType;
use OxidProfessionalServices\GraphQl\Core\Type\Enum\ImageSizeEnumType;
use OxidProfessionalServices\GraphQl\Core\Type\Field\HtmlFieldType;
use OxidProfessionalServices\GraphQl\Core\Type\Scalar\EmailType;
use OxidProfessionalServices\GraphQl\Core\Type\Scalar\UrlType;
use OxidProfessionalServices\GraphQl\Core\Type\Object\CategoryType;
use OxidProfessionalServices\GraphQl\Core\Type\Object\NodeType;

use OxidProfessionalServices\GraphQl\Core\Type\RootQueryType;
use OxidProfessionalServices\GraphQl\Core\Type\RootMutationType;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;


/**
 * Class Types - Acts as a registry and factory for Types.
 *
 * Each "type" is static ensuring that it will only be instantiated once and can be re-used
 * throughout the system.
 *
 */
class Types
{
    /**
     * Stores the root query type object
     *
     * @var RootQueryType object $rootQuery
     */
    private static $rootQuery;

    /**
     * Stores the root mutation type object
     *
     * @var RootQueryType object $rootMutation
     */
    private static $rootMutation;


    /**
     * Stores the category type object
     *
     * @var
     */
    private static $category;



    /**
     * Stores the node type object
     *
     * @var
     */
    private static $node;

    /**
     * Stores the image size enum type object
     *
     * @var
     */
    private static $imageSizeEnum;

    /**
     * Stores the content format enum type object
     *
     * @var
     */
    private static $contentFormatEnum;

    /**
     * Stores the url type object
     *
     * @var
     */
    private static $urlType;

    /**
     * Stores the email type object
     *
     * @var
     */
    private static $emailType;


    /**
     * Returns the definition for the RootQueryType
     * @return RootQueryType
     */
    public static function rootQuery()
    {
        return self::$rootQuery ?: (self::$rootQuery = new RootQueryType());
    }

    /**
     * Returns the definition for the RootQueryType
     * @return RootMutationType
     */
    public static function rootMutation()
    {
        return self::$rootMutation ?: (self::$rootMutation = new RootMutationType());
    }
    

    // Object Types
    /**
     * Returns the definition for the CategoryType
     *
     * @return CategoryType
     */
    public static function category()
    {
        return self::$category ?: (self::$category = new CategoryType());
    }


    // Interface types
    /**
     * Returns the definition for the NodeType
     *
     * @return NodeType
     */
    public static function node()
    {
        return self::$node ?: (self::$node = new NodeType());
    }

    // Enum types
    /**
     * Returns the definition for the ImageSizeEnumType
     *
     * @return ImageSizeEnumType
     */
    public static function imageSizeEnum()
    {
        return self::$imageSizeEnum ?: (self::$imageSizeEnum = new ImageSizeEnumType());
    }

    /**
     * Returns the definition for the ContentFormatEnumType
     *
     * @return ContentFormatEnumType
     */
    public static function contentFormatEnum()
    {
        return self::$contentFormatEnum ?: (self::$contentFormatEnum = new ContentFormatEnumType());
    }

    // Scalar types
    /**
     * Returns the definition for the email
     *
     * @return \GraphQL\Type\Definition\CustomScalarType
     */
    public static function email()
    {
        return self::$emailType ?: (self::$emailType = EmailType::create());
    }

    /**
     * Returns the definition for the url
     *
     * @return UrlType
     */
    public static function url()
    {
        return self::$urlType ?: (self::$urlType = new UrlType());
    }

    /**
     * Returns the definition for the htmlField
     *
     * @param $name
     * @param null $objectKey
     * @return array
     */
    public static function htmlField($name, $objectKey = null)
    {
        return HtmlFieldType::build($name, $objectKey);
    }


    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @return \GraphQL\Type\Definition\BooleanType
     */
    public static function boolean()
    {
        return Type::boolean();
    }

    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @return \GraphQL\Type\Definition\FloatType
     */
    public static function float()
    {
        return Type::float();
    }

    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @return \GraphQL\Type\Definition\IDType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @return \GraphQL\Type\Definition\IntType
     */
    public static function int()
    {
        return Type::int();
    }

    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @return \GraphQL\Type\Definition\StringType
     */
    public static function string()
    {
        return Type::string();
    }

    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @param Type $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @param $type
     * @return NonNull
     * @throws \Exception
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}