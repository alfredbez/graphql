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

class User extends BaseModel
{
    /**
     * Users list.
     *
     * @var array
     */
    protected $_aUsers = null;

    /**
     * User class construcotor.
     */
    public function __construct()
    {
        $this->getUsers();
    }

    /**
     * get Users from the DB
     * @return array|null
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getUsers()
    {
        if ($this->_aUsers == null) {
            $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            $sUsersView = getViewName('oxuser');
            $aUsers = $oDB->getAll("SELECT `OXID` AS id, `OXPASSWORD` AS password, `OXUSERNAME` AS email FROM `{$sUsersView}` WHERE `OXACTIVE` = 1");

            $this->_aUsers = $this->_buildUsers($aUsers);
        }
        //TODO add related categoryID
        return $this->_aUsers;
    }

    /**
     * Build categoies with the OXID as key.
     *
     * @param array $aUsers
     * @param array $aData
     * @param int   $iLevel
     */
    protected function _buildUsers($aUsers, &$aData = array(), $iLevel = 0)
    {
        if ($aUsers) {
            foreach ($aUsers as $aUser) {
                $aData[$aUser['id']] = $aUser;
            }
            asort($aData);
        }

        return $aData;
    }

    /**
     * Find a user by id.
     * @param $id
     *
     * @return array|null
     */
    public function findUser($id)
    {
        return isset($this->_aUsers[$id]) ? $this->_aUsers[$id] : null;
    }

    /**
     * Find users.
     *
     * @param int $limit
     * @param string $afterId
     *
     * @return array|null
     */
    public function findUsers($limit, $afterId = null)
    {
        $start = $afterId ? (int) array_search($afterId, array_keys($this->_aUsers)) + 1 : 0;

        return array_slice(array_values($this->_aUsers), $start, $limit);
    }
}
