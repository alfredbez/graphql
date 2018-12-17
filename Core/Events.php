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

namespace OxidProfessionalServices\GraphQL\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;

/**
 * Class defines what module does on Shop events.
 */
class Events
{
    /**
     * On module activation callback
     */
    public static function onActivate()
    {
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        $oxDb = DatabaseProvider::getDb();

        $bUpdateViews = false;
        $sql = "REPLACE INTO `oxseo` (`OXOBJECTID`, `OXIDENT`, `OXSHOPID`, `OXLANG`, `OXSTDURL`, `OXSEOURL`, `OXTYPE`, `OXFIXED`, `OXEXPIRED`, `OXPARAMS`, `OXTIMESTAMP`)
                VALUES ('c28b890370ed6c8ce7ac89968a904577', 'bb307611d3f88a265136160fd3f949c4', 1, 0, 'widget.php?cl=graphql', 'graphql/', 'static', 0, 0, '', '2018-12-17 12:00:00')";
        $oxDb->execute($sql);
        $bUpdateViews = true;

        if ($bUpdateViews) {
            $dbMetaDataHandler->updateViews();
        }
    }

    /**
     * On module deactivation callback
     */
    public static function onDeactivate()
    {
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        $oxDb = DatabaseProvider::getDb();

        $bUpdateViews = false;
        $sql = "DELETE FROM `oxseo` WHERE `OXIDENT` = 'bb307611d3f88a265136160fd3f949c4' AND `OXSHOPID` = 1 AND `OXLANG` = 0";
        $oxDb->execute($sql);
        $bUpdateViews = true;

        if ($bUpdateViews) {
            $dbMetaDataHandler->updateViews();
        }
    }
}