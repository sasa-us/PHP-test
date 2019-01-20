<?php
/***************************************************************************
	@extension	: Product Label.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class Magebees_Productlabel_Model_Mysql4_Productlabel_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productlabel/productlabel');
    }
    
    public function categoryFilter($category) 
    {

        $this->getSelect()->join(
            array('category_table' => $this->getTable('productlabel/categories')),
            'main_table.label_id = category_table.label_id',
            array()
        )
                ->where('category_table.category_ids = ?', $category);
        return $this;
    }
    
    public function productFilter($productsku) 
    {
        
        $this->getSelect()->join(
            array('product_table' => $this->getTable('productlabel/product')),
            'main_table.label_id = product_table.label_id',
            array()
        )
                ->where('product_table.product_sku = ?', $productsku);
        return $this;
    }
}
