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

use OxidProfessionalServices\GraphQl\Model\Article;
use OxidProfessionalServices\GraphQl\Model\User;
use OxidProfessionalServices\GraphQl\Model\Image;
use OxidProfessionalServices\GraphQl\Core\Types;

use GraphQL\Type\Definition\InterfaceType;

/**
 * Class NodeType
 *
 */
class NodeType extends InterfaceType
{
    /**
     * NodeType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => 'Node',
            'fields' => [
                'id' => Types::id()
            ],
            'resolveType' => [$this, 'resolveNodeType']
        ];

        parent::__construct($config);
    }

    /**
     * @param $object
     * @return UserType
     */
    public function resolveNodeType($object)
    {
        if ($object instanceof User) {
            return Types::user();
        } else if ($object instanceof Image) {
            return Types::image();
        } else if ($object instanceof Article) {
            return Types::article();
        }

    }
}