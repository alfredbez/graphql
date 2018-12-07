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

namespace OxidProfessionalServices\GraphQl\Controller;

use OxidEsales\Eshop\Core\Registry;

use OxidProfessionalServices\GraphQl\Core\Types;
use OxidProfessionalServices\GraphQl\Core\AppContext;

use GraphQL\Type\Schema;

use \GraphQL\Error\FormattedError;
use \GraphQL\Error\Debug;


/**
 * Class GraphQL graphql-php wrapper.
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\FrontendController
 */
class GraphQL extends \OxidEsales\Eshop\Application\Controller\FrontendController
{
    /**
     * Holds the Schema def
     * @var \GraphQL\Type\Schema
     */
    protected $schema;

    /**
     * GraphQL constructor.
     * @throws \Throwable
     */
    public function __construct()
    {
        /**
         * Disable default PHP error reporting - we have better one for debug mode (see bellow)
         */
        ini_set('display_errors', 0);
        $debug = false;
        if (!empty($_GET['debug'])) {
            set_error_handler(function($severity, $message, $file, $line) use (&$phpErrors) {
                throw new ErrorException($message, 0, $severity, $file, $line);
            });
            $debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;
        }

        try {
            /**
             * Prepare context that will be available in all field resolvers (as 3rd argument)
             */
            $oAppContext = $this->getAppContext();

            /**
             * Parse incoming query and variables
             */
            $aData = $this->initGraphQlRequest();

            /**
             * GraphQL schema to be passed to query executor:
             */
            $oSchema = $this->getSchema();

            /**
             * Executes the request and captures the result
             */
            $oGraphQL = oxNew(\GraphQL\GraphQL::class);
            $oResult = $oGraphQL->executeQuery(
                $oSchema,
                $aData['query'],
                null,
                $oAppContext,
                (array)$aData['variables']
            );

            $aOutput = $oResult->toArray($debug);

        } catch (\Exception $error) {
            $aOutput['errors'] = [
                FormattedError::createFromException($error, $debug)
            ];
        }

        $this->renderJsonResponse($aOutput);
    }

    /**
     * Returns the Schema as defined by static registrations throughout
     * the WP Load.
     */
    public  function getSchema()
    {
        if ( null === $this->schema ) {
            $oTypes = oxNew(Types::class);

            /**
             * Create an executable Schema from the registered
             * root_Query and root_mutation
             */
            $aExecutableSchema = [
                'query'    => $oTypes->rootQuery(),
                'mutation' => $oTypes->rootMutation(),
            ];

            /**
             * Generate the Schema
             */
            $this->schema = oxNew(Schema::class, $aExecutableSchema);
        }

        return $this->schema;
    }

    /**
     * @param $aResult
     */
    protected function renderJsonResponse($aResult)
    {
        /**
         * Force json content type by oxid framework
         */
        $_GET['renderPartial'] = 1;

        $oUtils = Registry::getUtils();
        $oUtils->setHeader('Content-Type: application/json');
        $oUtils->showMessageAndExit(json_encode($aResult));

    }

    /**
     * Get the AppContext for use in passing down the Resolve Tree
     * @return object|AppContext
     */
    private function getAppContext()
    {
        $oConfig = $this->getConfig();

        $oAppContext = oxNew(AppContext::class);
        $oAppContext->viewer = $this->getUser();
        $oAppContext->rootUrl = $oConfig->getShopUrl();
        $oAppContext->request = !empty( $_REQUEST ) ? $_REQUEST : null;
        return $oAppContext;
    }

    /**
     * @return array
     */
    private function initGraphQlRequest()
    {
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $sRaw = file_get_contents('php://input') ?: '';
            $aData = json_decode($sRaw, true) ?: [];
        } else {
            $aData = $_REQUEST;
        }

        $aData += ['query' => null, 'variables' => null];
        if (null === $aData['query']) {
            $aData['query'] = '{  Message: welcome }';
        }

        return $aData;
    }

}