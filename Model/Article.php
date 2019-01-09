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

class Article extends BaseModel
{
    /**
     * Articles list.
     *
     * @var array
     */
    protected $_aArticles = null;

    /**
     * Article class construcotor.
     */
    public function __construct()
    {
        //$this->getArticles();
    }

    /**
     * get Articles from the DB
     * @return array|null
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getArticles($limit)
    {
        if ($this->_aArticles == null) {
            $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            $sArticlesView = getViewName('oxarticles');
            $aArticles = $oDB->getAll("SELECT
                `OXID` AS id,
                `OXARTNUM` AS sku,
                `OXTITLE` AS title,
                `OXSHORTDESC` AS description,
                `OXPRICE` AS price,
                `OXTHUMB` AS thumb,
                `OXICON` AS icon,
                `OXPIC1` AS image,
                `OXPARENTID` AS parent
                FROM `{$sArticlesView}` WHERE `OXACTIVE` = 1 LIMIT {$limit}");

            $this->_aArticles = $this->_buildArticles($aArticles);
        }
        //TODO add related categoryID
        return $this->_aArticles;
    }

    /**
     * Build categoies with the OXID as key.
     *
     * @param array $aArticles
     * @param array $aData
     * @param int   $iLevel
     */
    protected function _buildArticles($aArticles, &$aData = array(), $iLevel = 0)
    {
        if ($aArticles) {
            foreach ($aArticles as $aArticle) {
                $aData[$aArticle['id']] = $aArticle;
            }
            asort($aData);
        }

        return $aData;
    }

    /**
     * Find a article by id.
     * @param $id
     *
     *
     * @return array|null
     */
    public function findArticle($id)
    {
        return isset($this->_aArticles[$id]) ? $this->_aArticles[$id] : null;
    }

    /**
     * Find articles.
     *
     * @param int $limit
     * @param string $afterId
     *
     * @return array|null
     */
    public function findArticles($limit, $afterId = null)
    {
        $this->getArticles($limit);
        $start = $afterId ? (int) array_search($afterId, array_keys($this->_aArticles)) + 1 : 0;

        return array_slice(array_values($this->_aArticles), $start, $limit);
    }

    /**
     * Find categories articles.
     *
     * @param int $limit
     * @param string $afterId
     *
     * @return array|null
     */
    public function findCategoryArticles($catId, $limit, $afterId = null)
    {
        //TODO
        $this->getArticles($limit);
        $start = $afterId ? (int) array_search($afterId, array_keys($this->_aArticles)) + 1 : 0;

        return array_slice(array_values($this->_aArticles), $start, $limit);
    }
}
