<?php
/**
 * This file is part of OXID eSales eVAT module.
 *
 * OXID eSales eVAT module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales eVAT module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales eVAT module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2014
 */

/**
 * VAT Groups db gateway class.
 */
class oeVATTBECategoryVATGroupsPopulatorDbGateway
{
    /**
     * Update category article with the same information
     *
     * @param string $sCategoryId category id
     */
    public function populate($sCategoryId)
    {
        $this->_deleteArticlesGroups($sCategoryId);
        $this->_setArticlesGroups($sCategoryId);
        $this->_setArticlesAsTBEServices($sCategoryId);
    }

    /**
     * Delete category articles VAT Group data from database.
     *
     * @param string $sCategoryId category id.
     *
     * @return bool
     */
    protected function _deleteArticlesGroups($sCategoryId)
    {
        $oDb = $this->_getDb();

        $sSql = '
          DELETE `oevattbe_articlevat`.*
          FROM `oevattbe_articlevat`
          INNER JOIN `oxobject2category` ON `oxobject2category`.`oxobjectid` = `oevattbe_articlevat`.`oevattbe_articleid`
          WHERE `oxobject2category`.`oxcatnid` = ' . $oDb->quote($sCategoryId);

        return $oDb->execute($sSql);
    }

    /**
     * Populates category info to articles
     *
     * @param string $sCategoryId categoryId
     *
     * @return bool
     */
    protected function _setArticlesGroups($sCategoryId)
    {
        $oDb = $this->_getDb();

        $sSql = 'INSERT INTO `oevattbe_articlevat` (`oevattbe_articleid`, `oevattbe_countryid`, `oevattbe_vatgroupid`)
              SELECT `oxobject2category`.`oxobjectid`, `oevattbe_categoryvat`.`oevattbe_countryid`, `oevattbe_categoryvat`.`oevattbe_vatgroupid`
              FROM `oevattbe_categoryvat`
              LEFT JOIN `oxobject2category` ON `oxobject2category`.`oxcatnid` = `oevattbe_categoryvat`.`oevattbe_categoryid`
              WHERE `oevattbe_categoryvat`.`oevattbe_categoryid` = '. $oDb->quote($sCategoryId);

        return $oDb->execute($sSql);
    }

    /**
     * Populates category info to articles
     *
     * @param string $sCategoryId categoryId
     *
     * @return bool
     */
    protected function _setArticlesAsTBEServices($sCategoryId)
    {
        $oDb = $this->_getDb();

        $sSql = 'UPDATE `oxarticles`
              INNER JOIN `oxobject2category` ON `oxobject2category`.`oxobjectid` = `oxarticles`.`oxid`
              LEFT JOIN `oxcategories` ON `oxobject2category`.`oxcatnid` = oxcategories.oxid
              SET  oxarticles.oevattbe_istbeservice = oxcategories.oevattbe_istbe
              WHERE `oxobject2category`.`oxcatnid` = '. $oDb->quote($sCategoryId);

        return $oDb->execute($sSql);
    }

    /**
     * Returns data base resource.
     *
     * @return oxLegacyDb
     */
    protected function _getDb()
    {
        return oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
    }
}
