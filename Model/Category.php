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
use OxidEsales\Eshop\Core\DatabaseProvider;

class Category extends \OxidEsales\EshopCommunity\Application\Model\Category
{
    /**
     * Categories list.
     *
     * @var array
     */
    protected $_aCategories = null;

    /**
     * Category class construcotor.
     */
    public function __construct()
    {
        $this->getCategories();
    }

    /**
     * get Categories from the DB
     * @return array|null
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getCategories()
    {
        if ($this->_aCategories == null) {
            $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            $sCategoriesView = getViewName('oxcategories');
            $aCategories = $oDB->getAll("SELECT `OXID` AS id,
                `OXTITLE` AS title,
                `OXTHUMB` AS thumb,
                `OXICON` AS icon,
                `OXPARENTID` AS parent
                FROM `{$sCategoriesView}` WHERE `OXACTIVE` = 1");

            $this->_aCategories = $this->_buildCategories($aCategories);
        }

        return $this->_aCategories;
    }

    /**
     * Build categoies with the OXID as key.
     *
     * @param array $aCategories
     * @param array $aData
     * @param int   $iLevel
     */
    protected function _buildCategories($aCategories, &$aData = array(), $iLevel = 0)
    {
        if ($aCategories) {
            foreach ($aCategories as $aCategory) {
                $aData[$aCategory['id']] = $aCategory;
            }
            asort($aData);
        }

        return $aData;
    }

    /**
     * Find a category by id.
     * @param $id
     *
     * @return array|null
     */
    public function findCategory($id)
    {
        return isset($this->_aCategories[$id]) ? $this->_aCategories[$id] : null;
    }

    /**
     * Find categories.
     *
     * @param int $limit
     * @param string $afterId
     *
     * @return array|null
     */
    public function findCategories($limit, $afterId = null)
    {
        $start = $afterId ? (int) array_search($afterId, array_keys($this->_aCategories)) + 1 : 0;

        return array_slice(array_values($this->_aCategories), $start, $limit);
    }
}
