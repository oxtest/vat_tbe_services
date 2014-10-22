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
 * Testing oeVATTBECountryVatGroups class.
 *
 * @covers oeVATTBECountryVatGroups
 * @covers oeVATTBECountryVATGroupsDbGateway
 * @covers oeVATTBECountryVATGroup
 * @covers oeVATTBECountryVATGroupsList
 */
class Integration_oeVatTbe_VATGroups_oeVATTBECountryVATGroupCreationTest extends OxidTestCase
{
    /**
     * Return different variants of country VAT group data to save.
     *
     * @return array
     */
    public function providerCreateNewGroup()
    {
        return array(
            array('small VAT', 5, 'some description', '5.00'),
            array('small VAT', 5, '', '5.00'),
            array('small VAT', 5.5, 'some description', '5.50'),
        );
    }

    /**
     * Test if new group creation works.
     *
     * @param string $sGroupName        group name.
     * @param float  $fVATRate          vat rate.
     * @param string $sGroupDescription group description.
     * @param string $sExpectedVatRate  vat rate after database formatting.
     *
     * @dataProvider providerCreateNewGroup
     */
    public function testCreateNewGroupWithSameData($sGroupName, $fVATRate, $sGroupDescription, $sExpectedVatRate)
    {
        $this->setTablesForCleanup('oevattbe_countryvatgroups');

        $sCountryId = 'some_country_id';
        $aParameters['oxcountry__oxid'] = $sCountryId;
        if ($sGroupName) {
            $aParameters['oevattbe_name'] = $sGroupName;
        }
        $aParameters['oevattbe_rate'] = $fVATRate;
        $aParameters['oevattbe_description'] = $sGroupDescription;

        $this->setRequestParam('editval', $aParameters);

        /** @var oeVATTBECountryVatGroups $oVATTBECountryVatGroups */
        $oVATTBECountryVatGroups = oxNew('oeVATTBECountryVatGroups');
        $oVATTBECountryVatGroups->addCountryVATGroup();
        $oVATTBECountryVatGroups->addCountryVATGroup();

        /** @var oeVATTBECountryVATGroupsDbGateway $oGateway */
        $oGateway = oxNew('oeVATTBECountryVATGroupsDbGateway');
        /** @var oeVATTBECountryVATGroupsList $oeVATTBECountryVATGroupsList */
        $oVATTBECountryVATGroupsList = oxNew('oeVATTBECountryVATGroupsList', $oGateway);
        $aVATTBECountryVATGroupsList = $oVATTBECountryVATGroupsList->load('some_country_id');

        $this->assertTrue(isset($aVATTBECountryVATGroupsList[0]), 'Newly created group must be in 0 position.');
        $this->assertTrue(isset($aVATTBECountryVATGroupsList[1]), 'Newly created group must be in 1 position.');

        /** @var oeVATTBECountryVATGroup $oNewlyCreatedCountryVATGroup */
        $oNewlyCreatedCountryVATGroup = $aVATTBECountryVATGroupsList[0];

        $this->assertSame($sCountryId, $oNewlyCreatedCountryVATGroup->getCountryId());
        $this->assertSame($sGroupName, $oNewlyCreatedCountryVATGroup->getName());
        $this->assertSame($sExpectedVatRate, $oNewlyCreatedCountryVATGroup->getRate());
        $this->assertSame($sGroupDescription, $oNewlyCreatedCountryVATGroup->getDescription());

        /** @var oeVATTBECountryVATGroup $oNewlyCreatedCountryVATGroup */
        $oNewlyCreatedCountryVATGroup = $aVATTBECountryVATGroupsList[1];

        $this->assertSame($sCountryId, $oNewlyCreatedCountryVATGroup->getCountryId());
        $this->assertSame($sGroupName, $oNewlyCreatedCountryVATGroup->getName());
        $this->assertSame($sExpectedVatRate, $oNewlyCreatedCountryVATGroup->getRate());
        $this->assertSame($sGroupDescription, $oNewlyCreatedCountryVATGroup->getDescription());
    }
}
