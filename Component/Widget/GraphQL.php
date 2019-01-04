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

namespace OxidProfessionalServices\GraphQl\Component\Widget;

use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\GraphQl\Core\AppContext;
use OxidProfessionalServices\GraphQl\Core\Auth;
use OxidProfessionalServices\GraphQl\Core\Types;
use GraphQL\Type\Schema;
use GraphQL\Error\FormattedError;
use GraphQL\Error\Debug;


/**
 * Class GraphQL graphql-php wrapper
 *
 * @mixin \OxidEsales\Eshop\Application\Component\Widget\WidgetController
 */
class GraphQL extends \OxidEsales\Eshop\Application\Component\Widget\WidgetController
{
    /**
     * The GraphQL Schema definition
     *
     * @var \GraphQL\Type\Schema
     */
    protected $_oSchema;

    /**
     * The Context fron the GraphQL App
     *
     * @var \OxidProfessionalServices\GraphQl\Core\AppContext
     */
    protected $_oAppContext;

    /**
     * Undocumented function
     *
     * @return void
     */
    public function init()
    {
        /**
         * Parse incoming query and variables
         */
        $aData = $this->getGraphQLRequest();
        $this->query($aData);
    }

    /**
     * GraphQL query
     *
     * @throws \Throwable
     */
    public function query($aData)
    {
        try {
            /**
             * Prepare context that will be available in all field resolvers (as 3rd argument)
             */
            $oAppContext = $this->getAppContext();

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
                $aData['variables']
            );

            $aOutput = $oResult->toArray(1);

        } catch (\Exception $error) {
            $aOutput['errors'] = [
                FormattedError::createFromException($error, true)
            ];
        }

        $this->renderJsonResponse($aOutput);
    }

    /**
     * Get the AppContext for use in passing down the Resolve Tree
     *
     * @return \OxidProfessionalServices\GraphQl\Core\AppContext
     */
    private function getAppContext()
    {
        $oAuth = oxNew(Auth::class);
        $aContext = $oAuth->authorize();

        $this->_oAppContext = oxNew(AppContext::class);
        $this->_oAppContext->viewer = $aContext->sub;
        $this->_oAppContext->rootUrl = $aContext->iss;
        $this->_oAppContext->request = !empty( $_REQUEST ) ? $_REQUEST : null;

        return $this->_oAppContext;
    }

    /**
     * Returns the Schema as defined by static registrations
     *
     * @return GraphQL\Type\Schema
     */
    public  function getSchema()
    {
        if ( null === $this->_oSchema ) {
            $oTypes = oxNew(Types::class);

            /**
             * Create an executable Schema from the registered
             * query, mutation and subscription
             */
            $aExecutableSchema = [
                'query'         => $oTypes->query(),
                'mutation'      => $oTypes->mutation(),
            ];

            /**
             * Generate the Schema
             */
            $this->_oSchema = oxNew(Schema::class, $aExecutableSchema);
        }

        return $this->_oSchema;
    }

    /**
     * Get the Request data
     *
     * @return array
     */
    private function getGraphQLRequest()
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

    /**
     * Return a JSON Object with the graphql results
     *
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
}