<?php
/***************************************************************************
	@extension	: Product Label.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class Magebees_Productlabel_Model_System_Config_Source_Pages
{

    public function toOptionArray() 
    {
        return array(
            array(
                'value' => 'product',
                'label' => Mage::helper('productlabel')->__('Product View Page')
            ),
            array(
                'value' => 'category',
                'label' => Mage::helper('adminhtml')->__('Product Listing Page')
            ),
            array(
                'value' => 'result',
                'label' => Mage::helper('adminhtml')->__('Search Page')
            ),
            array(
                'value' => 'advanced',
                'label' => Mage::helper('adminhtml')->__('Advanced Search Page')
            )
        );
    }
    
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $options = array('product'=>'Product View Page','category'=>'Product Listing Page','result'=>'Search Page','advanced'=>'Advanced Search Page');
        return $options;
    }
}