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
class Unit_oeVatTbe_components_oeVATTBEOxCmpbasketTest extends OxidTestCase
{
    /**
     * Render test
     */
    public function testRenderBasketWithoutTbeCountry()
    {
        $oUser = $this->getMock("oxUser", array("getTbeCountryId", 'hasVATTBEArticles'));
        $oUser->expects($this->any())->method("getTbeCountryId")->will($this->returnValue('DE'));

        $oBasket = $this->getMock("oeVATTBEOxBasket", array('hasVATTBEArticles'));
        $oBasket->expects($this->any())->method("hasVATTBEArticles")->will($this->returnValue(true));

        $this->getSession()->setBasket($oBasket);

        $oCmp_Basket = oxNew('oeVATTBEOxCmp_Basket');
        $oCmp_Basket->setUser($oUser);

        $oBasket = $oCmp_Basket->render();

        $this->assertSame('DE', $oBasket->getTbeCountryId());
        $this->assertTrue($oBasket->showTBECountryChangedError());
    }

    /**
     * Render test
     */
    public function testRenderBasketWithTbeCountry()
    {
        $oUser = $this->getMock("oxUser", array("getTbeCountryId", 'hasVATTBEArticles'));
        $oUser->expects($this->any())->method("getTbeCountryId")->will($this->returnValue('DE'));

        $oBasket = $this->getMock("oeVATTBEOxBasket", array('hasVATTBEArticles'));
        $oBasket->expects($this->any())->method("hasVATTBEArticles")->will($this->returnValue(true));
        $oBasket->setTBECountryId('LT');

        $this->getSession()->setBasket($oBasket);

        $oCmp_Basket = oxNew('oeVATTBEOxCmp_Basket');
        $oCmp_Basket->setUser($oUser);

        $oBasket = $oCmp_Basket->render();

        $this->assertSame('DE', $oBasket->getTbeCountryId());
        $this->assertTrue($oBasket->showTBECountryChangedError());
    }

    /**
     * Render test
     */
    public function testRenderBasketWithTbeCountryNoTBEArticles()
    {
        $oUser = $this->getMock("oxUser", array("getTbeCountryId", 'hasVATTBEArticles'));
        $oUser->expects($this->any())->method("getTbeCountryId")->will($this->returnValue('DE'));

        $oBasket = $this->getMock("oeVATTBEOxBasket", array('hasVATTBEArticles'));
        $oBasket->expects($this->any())->method("hasVATTBEArticles")->will($this->returnValue(false));
        $oBasket->setTBECountryId('LT');

        $this->getSession()->setBasket($oBasket);

        $oCmp_Basket = oxNew('oeVATTBEOxCmp_Basket');
        $oCmp_Basket->setUser($oUser);

        $oBasket = $oCmp_Basket->render();

        $this->assertSame('DE', $oBasket->getTbeCountryId());
        $this->assertFalse($oBasket->showTBECountryChangedError());
    }

    /**
     * Render test
     */
    public function testRenderBasketWithTbeSameCountry()
    {
        $oUser = $this->getMock("oxUser", array("getTbeCountryId", 'hasVATTBEArticles'));
        $oUser->expects($this->any())->method("getTbeCountryId")->will($this->returnValue('DE'));

        $oBasket = $this->getMock("oeVATTBEOxBasket", array('hasVATTBEArticles'));
        $oBasket->expects($this->any())->method("hasVATTBEArticles")->will($this->returnValue(true));
        $oBasket->setTBECountryId('DE');

        $this->getSession()->setBasket($oBasket);

        $oCmp_Basket = oxNew('oeVATTBEOxCmp_Basket');
        $oCmp_Basket->setUser($oUser);

        $oBasket = $oCmp_Basket->render();

        $this->assertSame('DE', $oBasket->getTbeCountryId());
        $this->assertFalse($oBasket->showTBECountryChangedError());
    }
}
