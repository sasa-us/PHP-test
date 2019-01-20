<?php
    /***************************************************************************
		@extension	: Product Label.
		@copyright	: Copyright (c) 2015 Capacity Web Solutions.
		( http://www.capacitywebsolutions.com )
		@author		: Capacity Web Solutions Pvt. Ltd.
		@support	: magento@capacitywebsolutions.com	
	***************************************************************************/
    
    class Magebees_Productlabel_Model_Productobserver
{
        
        protected $_labels = null;
        protected $labelDataCollection=array();    
        protected $label_product_mapper=array();
        
        //Get label collection  
        public function getLabelCollection()
        {
            $storeId = Mage::app()->getStore()->getStoreId();
            $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId(); 
            $this->_labels = Mage::getModel('productlabel/productlabel')->getCollection()
            ->addFieldToFilter('is_active', array('eq' => 1))
            ->addFieldToFilter('customer_group_ids', array(array('finset' => $groupId)))
            ->setOrder('sort_order', 'ASC')
            ->setOrder('label_id', 'ASC');
            
            if (!Mage::app()->isSingleStoreMode()) {
                $this->_labels->addFieldToFilter('stores', array(array('finset' => $storeId)));
            }

            $producttable = Mage::getSingleton('core/resource')->getTableName('cws_productlabel_product'); 
            $categorytable = Mage::getSingleton('core/resource')->getTableName('cws_productlabel_category');
            $this->_labels->getSelect()
            ->joinLeft(array('product' => $producttable), 'main_table.label_id = product.label_id', 'product.product_sku')
            ->joinLeft(array('category' => $categorytable), 'main_table.label_id = category.label_id', 'category.category_ids');
        }
        
        public function _filterCategoryLabel(Varien_Event_Observer $observer)
        {
            $event = $observer->getEvent();
            $productCollection = $event->getCollection();
            $this->getLabelCollection();
            foreach($productCollection as $product)
            {
                $this->setLabelByConditions($product);
            }
        }
        
        public function _filterProductLabel(Varien_Event_Observer $observer)
        {
            $event = $observer->getEvent();
            $product = $event->getProduct();
            $this->getLabelCollection();
            $this->setLabelByConditions($product);
            return $this;
        }
        
        public function setLabelByConditions($product)
        {
            $display_page = Mage::helper('productlabel')->getConfigDisplayLabelOnArray();
            $controller_name = Mage::app()->getRequest()->getControllerName();
            if(in_array($controller_name, $display_page)){
                $categoryIds = $product->getCategoryIds();
                foreach($this->_labels->getData() as $label)
                {
                    $label_id = $label['label_id'];
                    if(array_key_exists($label_id, $this->labelDataCollection)){
                        if(isset($label['product_sku'])){
                            $sku = $product->getSku();
                            if($label['product_sku']==$sku)    {
                                $this->labelDataCollection[$label_id]['product_sku']=$label['product_sku'];
                            }
                        }
 
                        if(isset($label['category_ids'])){
                            if(in_array($label['category_ids'], $categoryIds)){
                                $this->labelDataCollection[$label_id]['category_ids']=$label['category_ids'];
                            }
                        } 
                    }else{
                        $this->labelDataCollection[$label_id] = $label;
                        if(isset($label['product_sku'])){
                            $sku = $product->getSku();
                            if($label['product_sku']==$sku){
                                $this->labelDataCollection[$label_id]['product_sku']=$label['product_sku'];
                            }
                        }
                    }
                }

                $this->setActiveLabelId($product, $categoryIds);
            }

            return $this;
        }
        
        //Set Active Label Id to Product
        public function setActiveLabelId($product,$categoryIds)
        {
            $pro_id = $product->getId();
            foreach($this->labelDataCollection as $ld){
                $product->setLabelData($ld);
                if($ld['include_sku']==0 && $ld['include_cat']==0){
                    $this->label_product_mapper[$pro_id][]=$ld['label_id'];
                }elseif($ld['include_sku']==0 && $ld['include_cat']==1)    {
                    if(in_array($ld['category_ids'], $categoryIds)){
                        $this->label_product_mapper[$pro_id][]=$ld['label_id'];
                    }
                }

                if($ld['include_sku']==1){
                    if($product->getSku()!=$ld['product_sku'])//if product sku not selected then also this condition works
                    {
                        if($ld['include_cat']==0){
                            $this->label_product_mapper[$pro_id][]=$ld['label_id'];
                        }

                        if($ld['include_cat']==1){
                            if(in_array($ld['category_ids'], $categoryIds)){
                                $this->label_product_mapper[$pro_id][]=$ld['label_id'];
                            }
                        }
                    }
                }

                if($ld['include_sku']==2  && ($product->getSku()==$ld['product_sku'])){
                    if($ld['include_cat']==0){
                        $this->label_product_mapper[$pro_id][]=$ld['label_id'];
                    }

                    if($ld['include_cat']==1){
                        if(in_array($ld['category_ids'], $categoryIds)){
                            $this->label_product_mapper[$pro_id][]=$ld['label_id'];
                        }
                    }
                } 
            }

            if(isset($this->label_product_mapper[$pro_id])){
                $product->setLabels($this->label_product_mapper[$pro_id]);
            }
        }
    }
