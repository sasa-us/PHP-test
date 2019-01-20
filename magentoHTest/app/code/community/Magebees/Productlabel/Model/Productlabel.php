<?php
    /***************************************************************************
		@extension	: Product Label.
		@copyright	: Copyright (c) 2015 Capacity Web Solutions.
		( http://www.capacitywebsolutions.com )
		@author		: Capacity Web Solutions Pvt. Ltd.
		@support	: magento@capacitywebsolutions.com	
	***************************************************************************/
    
    class Magebees_Productlabel_Model_Productlabel extends Mage_Core_Model_Abstract
{
        protected $_info = array();
        
        public function _construct()
        {
            parent::_construct();
            $this->_init('productlabel/productlabel');
        }
        
        /**
            * set the product or category mode
        */
        public function init($mode,$p,$parent = null)
        {
            if ($mode){
                $this->setMode($mode == 'category' ? 'cat' : 'prod');
            }

            $regularPrice = $p->getPrice();
            $specialPrice = 0;
            $specialPrice = $p->getFinalPrice();
            
            if ($p->getTypeId() == 'bundle'){
                list($specialPrice, $maxPrice) = $p->getPriceModel()->getPrices($p);
                $regularPrice = $specialPrice;
                $price = $p->getData('special_price');
                if (!is_null($price) && $price < 100){
                    $regularPrice = ($specialPrice / $price) * 100;
                }
            }
            
            if($p->getTypeId() == 'grouped'){
                $asso_prod = $p->getTypeInstance(true)->getAssociatedProducts($p);
                foreach ($asso_prod as $child) {
                    if ($child != $p) {
                        $regularPrice += $child->getPrice();
                        $specialPrice += $child->getFinalPrice();
                    }
                }
            }
            
            $this->_info['price']         = $regularPrice;
            $this->_info['special_price'] = $specialPrice;
        }
        
        /**
            * Validate date range
            *
            * @param Varien_Object $object
            *
            * @return bool|array - return true if validation passed successfully. Array with errors description otherwise
        */
        
        public function validateData(Varien_Object $object)
        {
            $result   = array();
            $fromDate = $toDate = null;
            $fromPrice = $toPrice = null;
            if ($object->hasFromDate() && $object->hasToDate()) {
                $fromDate = $object->getFromDate();
                $toDate = $object->getToDate();
            }

            if ($fromDate && $toDate) {
                $fromDate = new Zend_Date($fromDate, Varien_Date::DATETIME_INTERNAL_FORMAT);
                $toDate = new Zend_Date($toDate, Varien_Date::DATETIME_INTERNAL_FORMAT);
                
                if ($fromDate->compare($toDate) === 1) {
                    $result[] = Mage::helper('productlabel')->__('End Date must be greater than Start Date.');
                }
            }

            if ($object->hasFromPrice() && $object->hasToPrice()) {
                $fromPrice = $object->getFromPrice();
                $toPrice = $object->getToPrice();
                if($fromPrice > $toPrice) {
                    $result[] = Mage::helper('productlabel')->__('From Price must be greater than To Price.');
                }
            }

            return !empty($result) ? $result : true;
        }
        
        /**
            * Get the image url of label
        */
        public function getImageUrl()
        {
            if(!$this->getValue('image')){
                $display_default = Mage::getStoreConfig('productlabel/general/image');
                if($display_default){
                    return $this->getDefaultImageUrl();
                }else{
                    return false;
                }
            }

            if($this->getMode()=="prod"){
                return Mage::getBaseUrl('media') . 'custom/product/resized/'.$this->getValue('image');
            }

            if($this->getMode()=="cat"){
                return Mage::getBaseUrl('media') . 'custom/category/resized/'.$this->getValue('image');
            }
        }
        
        public function getDefaultImageUrl()
        {
            if($this->getMode()=="prod"){
                return Mage::getDesign()->getSkinUrl('images/productlabel/label_product_default_bg.png');
            }

            if($this->getMode()=="cat"){
                return Mage::getDesign()->getSkinUrl('images/productlabel/label_category_default_bg.png');
            }
        }
        
        /**
            * Get the label position 
        */
        public function getCssClass()
        {
            $all = $this->getAvailablePositions(false);
            return $all[$this->getValue('position')];
        }  
        
        public function getValue($val)
        {
            return $this->_getData($this->getMode() . '_' . $val);  
        }
        
        public function getAvailablePositions($asText = true)
        {
            $a = array();
            foreach (array('top', 'middle', 'bottom') as $first){
                foreach (array('left', 'center', 'right') as $second){
                    $pos = ucfirst($first[0]).ucfirst($second[0]);
                    $a[$pos] = $asText ? 
                    Mage::helper('productlabel')->__(ucwords($first . ' ' . $second)) 
                    : 
                    $first . '-' . $second;
                }
            }
  
            return $a;     
        }
        
        /**
            * Get the label text
        */
        public function getText()
        {
            $txt = $this->getValue('text');
            
            $vars = array();
            preg_match_all('/{([a-zA-Z:\_0-9]+)}/', $txt, $vars);
            if (!$vars[1]){
                return $txt;    
            }
            
            $vars = $vars[1];
            $store = Mage::app()->getStore();
            $p = $this->getProduct();
            $this->_info['price']         = $p->getPrice();
            if($p->getSpecialPrice()){
                $this->_info['special_price'] = $p->getSpecialPrice();
            }else{
                $this->_info['special_price'] = $p->getFinalPrice();
            }
        
            foreach ($vars as $var){
                $value = '';
                switch ($var){
                    case 'PRICE': 
                    if($this->_info['price'])
                        $value = strip_tags($store->convertPrice($this->_info['price'], true));
                        break;
                    case 'SPECIAL_PRICE': 
                    if($this->_info['price'] && $this->_info['special_price']) 
                        $value = strip_tags($store->convertPrice($this->_info['special_price'], true));
                        break;
                    case 'FINAL_PRICE': 
                    if($this->_info['price'])
                        $value = strip_tags($store->convertPrice(Mage::helper('tax')->getPrice($p, $p->getFinalPrice()), true, false));
                        break;
                    case 'FINAL_PRICE_INCL_TAX':
                    if($this->_info['price'])                    
                        $value = strip_tags($store->convertPrice(Mage::helper('tax')->getPrice($p, $p->getFinalPrice(), true), true, false));
                        break;
                    
                    case 'SAVE_AMOUNT': 
                    if($this->_info['price'])
                        $value = strip_tags($store->convertPrice($this->_info['price'] - $this->_info['special_price'], true));
                        break;
                    
                    case 'SAVE_PERCENT': 
                    if($this->_info['price']){
                        $discount_price = $p->getFinalPrice();
                        $value = $this->_info['price'] - $discount_price;
                        $value = round($value * 100 / $this->_info['price']);
                    }
                        break;
                    
                    case 'BR': 
                    $value = '<br/>';
                        break;  
                    
                    case 'SKU': 
                    $value = $p->getSku();
                        break; 
                    
                    case 'NEW_FOR': 
                    $createdAt = strtotime($p->getCreatedAt());
                    $value = max(1, floor((time() - $createdAt) / 86400));                  
                        break;
                }
   
                $txt = str_replace('{' . $var . '}', $value, $txt);
            }
            
            return $txt;
        }
        
        public function isValid()
        {
            $p = $this->getProduct();
            
            //check for attribute
            $attr_code = $this->getAttrCode();
            if($attr_code){
                if(!array_key_exists($attr_code, $p->getData())){
                    return false;
                }

                $attr_val = $p->getData($attr_code);
                if($attr_code=='group_price' || $attr_code=='tier_price'){
                    $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
                    if(is_array($attr_val) && !empty($attr_val)){
                        foreach($attr_val as $attr_val){
                            if($attr_val['cust_group']==$groupId || $attr_val['all_groups']==1){
                                $attr_val = $attr_val['price'];    
                                break;
                            }
                        }
                    }else{
                        return false;
                    }
                }
                
                if (preg_match('/^[0-9,]+$/', $attr_val)){
                    if (!in_array($this->getAttrValue(), explode(',', $attr_val))){
                        return false;                 
                    }
                }
                elseif ($attr_val != $this->getAttrValue()){
                    return false; 
                }
            }
            
            //check for new product
            if ($this->getIsNew()){
                $isNew = $this->isNew($p) ? 2 : 1;
                if ($this->getIsNew() != $isNew){
                    return false;
                }
            }
            
            //check for on sale product
            if($this->getIsSale()){
                $isSale = $this->isSale($p) ? 2 : 1;
                if ($this->getIsSale() != $isSale){
                    return false;
                }
            }
            
            //check date range
            $now = Mage::getModel('core/date')->date();
            if ($this->getDateEnabled() && ($now < $this->getFromDate() || $now > $this->getToDate())) {
                return false;
            }
            
            //check price range
            if ($this->getPriceEnabled()) {
                switch ($this->getByPrice()) {
                    case '0': // Base Price
                    $price =  $p->getPrice();
                        break;
                    case '1': // Special Price
                    $price = $p->getSpecialPrice(); 
                        break;
                    case '2': // Final Price
                    $price = Mage::helper('tax')->getPrice($p, $p->getFinalPrice());
                        break;
                    case '3': // Final Price Incl Tax
                    $price = Mage::helper('tax')->getPrice($p, $p->getFinalPrice(), true);
                        break;
                }

                if ($price < $this->getFromPrice() || $price > $this->getToPrice()) {
                    return false;
                }
            }
            
            //check stock status
            $stockStatus = $this->getStockStatus();
            if ($stockStatus){
                $inStock = $p->getStockItem()->getIsInStock() ? 1 : 2;
                if ($inStock != $stockStatus){
                    return false;
                }
            }
            
            return true;
        }
        
        public function isNew($p)
        {
            $fromDate = '';
            $toDate   = '';
            
            $fromDate = $p->getNewsFromDate();
            $toDate   = $p->getNewsToDate();
            if($fromDate){
                if (time() < strtotime($fromDate)){
                    return false;     
                }

                if($toDate){
                    if (time() > strtotime($toDate)){
                        return false;
                    }
                }

                return true;
            }
            else{
                return false;
            }
        }
        
        public function isSale($p)
        {
            if ($this->_info['price']>$this->_info['special_price']){
                return true;
            }
            else{
                return false;
            }
        }
        
    }    