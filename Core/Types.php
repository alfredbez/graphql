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
use OxidProfessionalServices\GraphQl\Core\Type\ActionType;
use OxidProfessionalServices\GraphQl\Core\Type\ArticleType;
use OxidProfessionalServices\GraphQl\Core\Type\CategoryType;
use OxidProfessionalServices\GraphQl\Core\Type\UserType;
use OxidProfessionalServices\GraphQl\Core\Type\NodeType;

use OxidProfessionalServices\GraphQl\Core\Mutation\LogInMutation;
use OxidProfessionalServices\GraphQl\Core\Mutation\SignUpMutation;

use OxidProfessionalServices\GraphQl\Core\QueryRoot;
use OxidProfessionalServices\GraphQl\Core\MutationRoot;

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
     */
    private static $query;

    /**
     * Stores the root mutation type object
     *
     */
    private static $mutation;

    /**
     * Query types
     *
     */
    private static $action;
    private static $article;
    private static $category;
    private static $user;

    /**
     * Mutation types
     *
     */
    private static $signup;
    private static $login;

    /**
     * Interface types
     *
     */
    private static $node;

    /**
     * Enum types
     *
     */
    private static $imageSizeEnum;
    private static $contentFormatEnum;
    private static $urlType;

    /**
     * Scalar types
     *
     */
    private static $emailType;


    /**
     * Returns the definition for the QueryRoot type
     *
     * @return \OxidProfessionalServices\GraphQl\Core\QueryRoot
     */
    public static function query()
    {
        return self::$query ?: (self::$query = new QueryRoot());
    }

    /**
     * Returns the definition for the MutationRoot type
     * @return \OxidProfessionalServices\GraphQl\Core\MutationRoot
     */
    public static function mutation()
    {
        return self::$mutation ?: (self::$mutation = new MutationRoot());
    }

    /**
     * Returns the definition for the ActionType
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Object\ActionType
     */
    public static function action()
    {
        return self::$action ?: (self::$action = new ActionType());
    }

    /**
     * Returns the definition for the ArticleType
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Object\ArticleType
     */
    public static function article()
    {
        return self::$article ?: (self::$article = new ArticleType());
    }

    /**
     * Returns the definition for the CategoryType
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Object\CategoryType
     */
    public static function category()
    {
        return self::$category ?: (self::$category = new CategoryType());
    }

    /**
     * Returns the definition for the UserType
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Object\UserType
     */
    public static function user()
    {
        return self::$user ?: (self::$user = new UserType());
    }

    /**
     * Returns the definition for the LogInMutation
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Object\LogInMutation
     */
    public static function login()
    {
        return self::$login ?: (self::$login = new LogInMutation());
    }

    /**
     * Returns the definition for the SignUpMutation
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Mutation\SignUpMutation
     */
    public static function signup()
    {
        return self::$signup ?: (self::$signup = new SignUpMutation());
    }

    /**
     * Returns the definition for the NodeType
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Object\NodeType
     */
    public static function node()
    {
        return self::$node ?: (self::$node = new NodeType());
    }

    /**
     * Returns the definition for the ImageSizeEnumType
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Enum\ImageSizeEnumType
     */
    public static function imageSizeEnum()
    {
        return self::$imageSizeEnum ?: (self::$imageSizeEnum = new ImageSizeEnumType());
    }

    /**
     * Returns the definition for the ContentFormatEnumType
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Enum\ContentFormatEnumType
     */
    public static function contentFormatEnum()
    {
        return self::$contentFormatEnum ?: (self::$contentFormatEnum = new ContentFormatEnumType());
    }

    /**
     * Returns the definition for the email
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Scalar\EmailType
     */
    public static function email()
    {
        return self::$emailType ?: (self::$emailType = EmailType::create());
    }

    /**
     * Returns the definition for the url
     *
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Scalar\UrlType
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
     * @return \OxidProfessionalServices\GraphQl\Core\Type\Field\HtmlFieldType
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
     * @param \GraphQL\Type\Definition\Type|\GraphQL\Type\DefinitionContainer $type
     * @return \GraphQL\Type\Definition\ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * This is a wrapper for the GraphQL type to give a consistent experience
     *
     * @param \GraphQL\Type\Definition\Type|\GraphQL\Type\DefinitionContainer $type
     * @return \GraphQL\Type\Definition\NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}