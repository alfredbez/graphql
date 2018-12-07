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

namespace OxidProfessionalServices\GraphQl\Model;

use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\DatabaseProvider;

class Category extends BaseModel
{
    /**
     * @var null
     */
    protected $_aCategories = null;

    /**
     * @return array|null
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getCategories()
    {
        if( $this->_aCategories == null ) {

            $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            $sCategoriesView = getViewName('oxcategories');
            $aCategories = $oDB->getAll("SELECT `OXID` AS id,  `OXTITLE` AS title, `OXPARENTID` AS parent FROM `{$sCategoriesView}` WHERE `OXACTIVE` = 1");

            //TODO convert to an object in order to use the id as key
            $this->_aCategories = $aCategories;
        }

        return $this->_aCategories;

    }

    /**
     * @param $id
     * @return |null
     */
    public function findCategory($id)
    {
        return isset($this->_aCategories[$id]) ? $this->_aCategories[$id] : null;
    }

    /**
     * @param $limit
     * @param null $afterId
     * @return array
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function findCategories($limit, $afterId = null)
    {
        $this->getCategories();

        $start = $afterId ? (int) array_search($afterId, array_keys($this->_aCategories)) + 1 : 0;
        return array_slice(array_values($this->_aCategories), $start, $limit);
    }
}