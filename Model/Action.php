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

class Action extends BaseModel
{
    /**
     * Actions list.
     *
     * @var array
     */
    protected $_aActions = null;

    /**
     * Action class construcotor.
     */
    public function __construct()
    {
        //$this->getActions();
    }

    /**
     * get Actions from the DB
     * @return array|null
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function findActions($limit, $group = 3)
    {
        if ($this->_aActions == null) {
            $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            $sActionsView = getViewName('oxactions');
            $aActions = $oDB->getAll("SELECT `OXID` AS id,
                `OXTITLE` AS title,
                `OXLONGDESC` AS description,
                `OXPIC` AS image,
                `OXLINK` AS link
                FROM `{$sActionsView}` WHERE `OXTYPE`={$group} AND`OXACTIVE` = 1");

            $this->_aActions = $this->_buildActions($aActions);
        }
        //TODO add related categoryID
        return $this->_aActions;
    }

    /**
     * Build categoies with the OXID as key.
     *
     * @param array $aActions
     * @param array $aData
     * @param int   $iLevel
     */
    protected function _buildActions($aActions, &$aData = array(), $iLevel = 0)
    {
        if ($aActions) {
            foreach ($aActions as $aAction) {
                $aData[$aAction['id']] = $aAction;
            }
            asort($aData);
        }

        return $aData;
    }

    /**
     * Find a action by id.
     * @param $id
     *
     * @return array|null
     */
    public function findAction($id)
    {
        return isset($this->_aActions[$id]) ? $this->_aActions[$id] : null;
    }

    /**
     * Find actions.
     *
     * @param int $limit
     * @param string $group
     *
     * @return array|null
     */
    public function getActions($limit, $group = 3)
    {
        $start = $group ? (int) array_search($group, array_keys($this->_aActions)) + 1 : 0;

        return array_slice(array_values($this->_aActions), $start, $limit);
    }
}
