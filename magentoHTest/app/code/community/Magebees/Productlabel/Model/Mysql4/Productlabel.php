<?php

class Magebees_Productlabel_Model_Mysql4_Productlabel extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('productlabel/productlabel', 'label_id');
    }
    
    /* protected function _getLoadSelect($field, $value, $object)
	{
		$select = parent::_getLoadSelect($field, $value, $object);
		$select->joinLeft(
		array('product' => 'cws_productlabel_product'),$this->getMainTable() . '.label_id = product.label_id',array('product_sku'));
		return $select;
	} */
    
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);
        if(Mage::app()->getRequest()->getActionName()=='massStatus')
        {
            return $this;
        }
        
        if(!$object->getIncludeSku()){
            $object->setProductSku(null);
        }
            
        if($object->getStores()) {
            /* if(in_array('0',$object->getStores())){
				$object->setStores('0');
			}
			else{ */
                $object->setStores(implode(",", $object->getStores()));
            //}
        }
                
        if($object->getCategoryIds()){
            $object->setData('include_cat', '1');
        }
        else{
            $object->setData('include_cat', '0');
        }
    
        if(is_array($object->getCustomerGroupIds())){
            $object->setCustomerGroupIds(implode(",", $object->getCustomerGroupIds()));
        }
        
        if ($object->getData('from_date') instanceof Zend_Date) {
            $object->setData('from_date', $object->getData('from_date')->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }

        if ($object->getData('to_date') instanceof Zend_Date) {
            $object->setData('to_date', $object->getData('to_date')->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }

        if ($object->getData('from_date') == '') {
            $object->setData('from_date', null);
        }
        
        if ($object->getData('to_date') == '') {
            $object->setData('to_date', null);
        }
        
        return $this;
    }
    
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $id = Mage::app()->getRequest()->getParam('id');
            
        //save product skus
        $product_skus = explode(',', $object->getProductSku());
        if (is_array($product_skus)) {
            $product_skus = array_unique($product_skus);
        }

        $product_model = Mage::getModel('productlabel/product');
        if($id) {            
            $prd_data = $product_model->getCollection()
                            ->addFieldToFilter('label_id', $id); 
            $prd_data->walk('delete');  
        }

        if ($object->getProductSku()) {
            foreach($product_skus as $product)    {
                if($product){
                    $data_prd['label_id'] = $object->getId();
                    $data_prd['product_sku'] = trim($product);
                    $product_model->setData($data_prd);
                    $product_model->save();
                }
            }
        }
        
        //save category ids
        $categoryIds = $object->getCategoryIds();
        if (!is_array($categoryIds)) {
            $categoryIds = explode(',', (string) $categoryIds);
        }

        if (is_array($categoryIds)){
            $categoryIds = array_unique($categoryIds);
        }

        $cate_model = Mage::getModel('productlabel/categories');
        if($id) {            
            $cate_data = $cate_model->getCollection()
                            ->addFieldToFilter('label_id', $id); 
            $cate_data->walk('delete');  
        }

        if ($object->getCategoryIds()) {
            foreach($categoryIds as $category_id){
                if($category_id){
                    $data_cate['label_id'] = $object->getId();
                    $data_cate['category_ids'] = $category_id;
                    $cate_model->setData($data_cate);
                    $cate_model->save();
                }
            }
        }

        return parent::_afterSave($object);
    }
}
