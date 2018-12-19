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

use \OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration;


/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = [
    'id'            => 'oxps/graphql',
    'title'         => [
        'de'        =>  'OXPS :: GraphQL',
        'en'        =>  'OXPS :: GraphQL',
    ],
    'description'   =>  [
        'de' => '<span>OXPS GraphQL API-Modul</span>',

        'en' => '<span>OXPS GraphQL API Module</span>',
    ],
    'thumbnail'   => 'out/pictures/picture.png',
    'version'     => '0.0.1',
    'author'      => 'OXID Professional Services',
    'url'         => 'www.oxid-esales.com',
    'email'       => 'ps@oxid-esales.com',
    'extend'      => [
        ModuleConfiguration::class      => OxidProfessionalServices\GraphQl\Controller\Admin\GraphQLConfiguration::class,
    ],
    'controllers' => [
        // Admin Controller
        'graphiql'      => OxidProfessionalServices\GraphQl\Controller\Admin\GraphiQL::class,
        // Widget Controller
        'graphql'       => OxidProfessionalServices\GraphQl\Component\Widget\GraphQL::class,
    ],
    'templates'   => [
        //Admin
        'graphiql.tpl'    =>  'oxps/graphql/out/js/graphiql.tpl',
    ],
    'blocks'      => [
        [
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_var_type_str',
            'file'     => 'views/admin/blocks/admin_module_config_var_type_str.tpl',
        ]
    ],
    'settings'    => [
        [
            'group' =>  'credentials',
            'name'  =>  'strGraphQLApiKey',
            'type'  =>  'str',
            'value' =>  '',
            'action' => 'generateApiKey'
        ],
        [
            'group' =>  'credentials',
            'name'  =>  'strGraphQLApiSecret',
            'type'  =>  'str',
            'value' =>  '',
            'action' => 'generateApiSecret'
        ],
    ],
    'events'      => [
        'onActivate'   => 'OxidProfessionalServices\\GraphQl\\Core\\Events::onActivate',
        'onDeactivate' => 'OxidProfessionalServices\\GraphQl\\Core\\Events::onDeactivate'
    ]
];