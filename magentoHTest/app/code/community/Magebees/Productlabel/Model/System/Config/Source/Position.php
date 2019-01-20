<?php
/***************************************************************************
	@extension	: Product Label.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class Magebees_Productlabel_Model_System_Config_Source_Position
{

    public function toOptionArray() 
    {
        return array(
            array(
                'value' => 'TL',
                'label' => Mage::helper('adminhtml')->__('Top-Left')
            ),
            array(
                'value' => 'TC',
                'label' => Mage::helper('adminhtml')->__('Top-Center')
            ),
            array(
                'value' => 'TR',
                'label' => Mage::helper('adminhtml')->__('Top-Right')
            ),
            array(
                'value' => 'ML',
                'label' => Mage::helper('adminhtml')->__('Middle-Left')
            ),
            array(
                'value' => 'MC',
                'label' => Mage::helper('adminhtml')->__('Middle-Center')
            ),
            array(
                'value' => 'MR',
                'label' => Mage::helper('adminhtml')->__('Middle-Right')
            ),
            array(
                'value' => 'BL',
                'label' => Mage::helper('adminhtml')->__('Bottom-Left')
            ),
            array(
                'value' => 'BC',
                'label' => Mage::helper('adminhtml')->__('Bottom-Center')
            ),
            array(
                'value' => 'BR',
                'label' => Mage::helper('adminhtml')->__('Bottom-Right')
            )
        );
    }
}