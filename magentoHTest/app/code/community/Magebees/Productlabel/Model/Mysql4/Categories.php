<?php
/***************************************************************************
	@extension	: Product Label.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class Magebees_Productlabel_Model_Mysql4_Categories extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('productlabel/categories', 'label_category_id');
    }
}
