<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Form
{
    
    public function __construct() 
    {
        parent::__construct();
        $this->setTemplate('productlabel/category/edit/tab/product.phtml');
    }

    protected function getProductIds() 
    {
        
        $data = Mage::registry('productlabel_data');
        $prd_model = Mage::getModel('productlabel/product')->getCollection()
            ->addFieldToFilter('label_id', array('eq' => $data->getData('label_id')));
        
        $_productList = array();
        
        foreach($prd_model as $prd_data){
            $_productList[] = $prd_data->getData('product_sku');
        } 
    
        return is_array($_productList) ? $_productList : array();
    }

    public function getIdsString() 
    {
        return implode(',', $this->getProductIds());
    }
    
}
