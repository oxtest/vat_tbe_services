<?php
/**
 * This file is part of OXID eSales VAT TBE module.
 *
 * OXID eSales PayPal module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales PayPal module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales VAT TBE module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2014
 */


/**
 * Testing extended oxUser class.
 */
class Unit_oeVatTbe_models_oeVATTBEOxUserTest extends OxidTestCase
{
    public function testTBECountryIdSelecting()
    {
        $oConfig = $this->getConfig();
        $oConfig->setConfigParam('TBECountryEvidences', array('oeVATTBEBillingCountryEvidence'));
        $oConfig->setConfigParam('sDefaultTBEEvidence', 'billing_country');

        $oUser = oxNew('oeVATTBEOxUser');
        $oUser->oxuser__oxcountryid = new oxField('GermanyId');

        $this->assertEquals('GermanyId', $oUser->getTbeCountryId());
    }

    public function testIdentificationIfUserIsLocalWhenUserIsLocal()
    {
        $oConfig = $this->getConfig();
        $oConfig->setConfigParam('TBECountryEvidences', array('oeVATTBEBillingCountryEvidence'));
        $oConfig->setConfigParam('sDefaultTBEEvidence', 'billing_country');
        $oConfig->setConfigParam('aHomeCountry', array('LithuaniaId', 'GermanyId'));

        $oUser = oxNew('oeVATTBEOxUser');
        $oUser->oxuser__oxcountryid = new oxField('GermanyId');

        $this->assertEquals(true, $oUser->isLocalUser());
    }

    public function testIdentificationIfUserIsLocalWhenUserIsNotLocal()
    {
        $oConfig = $this->getConfig();
        $oConfig->setConfigParam('TBECountryEvidences', array('oeVATTBEBillingCountryEvidence'));
        $oConfig->setConfigParam('sDefaultTBEEvidence', 'billing_country');
        $oConfig->setConfigParam('aHomeCountry', array('LithuaniaId', 'FranceId'));

        $oUser = oxNew('oeVATTBEOxUser');
        $oUser->oxuser__oxcountryid = new oxField('GermanyId');

        $this->assertEquals(false, $oUser->isLocalUser());
    }
}