<?php
/***************************************************************************
	@extension	: Product Label.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class Magebees_Productlabel_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_labels = null;
    protected $_sizes = array();
    
    /*
		* config paths
    */

    //magento wide
    
    const MODULE_DISABLE_OUTPUT_PATH = 'advanced/modules_disable_output/Magebees_Productlabel';
    
    //productlabel wide
    const MODULE_ENABLE = 'productlabel/general/enable';
    const DISPLAY_LABEL_ON_PAGES = 'productlabel/general/display_label_on';
    
    
    public function isProductlabelEnabled()
    {
        $productlabel_disabled = Mage::getStoreConfig(self::MODULE_DISABLE_OUTPUT_PATH); 
        return ($productlabel_disabled != "1");
    }
    
    public function getConfigDisplayLabelOnArray()
    {
        $display_label_on_array = explode(',', Mage::getStoreConfig(self::DISPLAY_LABEL_ON_PAGES));
        return $display_label_on_array;
    }
        
    public function getLabel($product, $mode='category')
    {
        $html = '';
        $enabled = Mage::getStoreConfig(self::MODULE_ENABLE);
        if($enabled && $this->isProductlabelEnabled()){
            $_labels = $product->getLabels();
            if(count($_labels)){    
                $applied = false;
                foreach($_labels as $l){
                    $label = Mage::getModel('productlabel/productlabel')->load($l);
                    $label->setProduct($product);
                    $label->init($mode, $product);
                    if ($label->getHide() && $applied) {
                        continue;
                    }

                    if($label->isValid()){
                        $applied = true;
                        $html .= $this->_generateHtml($label);
                    }
                }
            }
        }

        return $html;
    }
    
    protected function _generateHtml($label)
    {    
        $html = '';
        $imgUrl = $label->getImageUrl();
        if($imgUrl){
            if (empty($this->_sizes[$imgUrl])){
                $image_info = getimagesize($imgUrl); 
                $this->_sizes[$imgUrl]['h']=$image_info[1];
                $this->_sizes[$imgUrl]['w']=$image_info[0];
            }
        
            $tableClass = $label->getCssClass();
                 
            $tableStyle = '';
            $tableStyle .= 'height:' . $this->_sizes[$imgUrl]['h'] . 'px; ';
            $tableStyle .= 'width:'  . $this->_sizes[$imgUrl]['w'] . 'px; ';
            
            $tableStyle .= $this->_getPositionAdjustment($tableClass, $this->_sizes[$imgUrl]);    
           
            if ($label->getMode() == 'cat') {
                $textStyle = "color:".$label->getCatTextColor().";";
                if($label->getCatTextSize()){
                    $textStyle = $textStyle."font-size:".$label->getCatTextSize()."px;";
                }
            } else {
                $textStyle = "color:".$label->getProdTextColor().";";
                if($label->getProdTextSize()){
                    $textStyle = $textStyle."font-size:".$label->getProdTextSize()."px;";
                }
            }
            
            if ($textStyle) {
                $textStyle = 'style="' . $textStyle . '"';
            }
        
            $html =  '<table class="productlabel-table ' . $tableClass . '" style ="' . $tableStyle . '">';
            $html .= '<tr>';
            $html .= '<td style="background: url(' . $imgUrl .') no-repeat 0 0">';
            $html .= '<span class="productlabel-txt" ' . $textStyle . '>' . $label->getText() . '</span>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }

        return $html;        
    }
    
    protected function _getPositionAdjustment($tableClass, $sizes)
    {
           $style = '';
        
        if ('top-center' == $tableClass){
             $style .= 'margin-left:' . (-$sizes['w'] / 2) . 'px;'; 
        }
        elseif (false !== strpos($tableClass, 'center')){
            $style .= 'margin-right:' . (-$sizes['w'] / 2) . 'px;';            
        }

        if (false !== strpos($tableClass, 'middle')){
            $style .= 'margin-top:'. (-$sizes['h'] / 2) .'px;';            
        }        

        return $style;
    }  
    
    public function resizeImg($fileName,$field)
    {
        $labelconfig = Mage::getStoreConfig('productlabel/labelsize');
        $imageURL = $fileName;
        $dir = "resized";
        
        if($field=="cat_image")    {
            $width=$labelconfig['width_category'];
            $height=$labelconfig['height_category'];
            $basePath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'category' . DS . $fileName;
            $newPath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'category' . DS . $dir . DS . $fileName;
        }elseif($field=="prod_image"){
            $width=$labelconfig['width_product'];
            $height=$labelconfig['height_product'];
            $basePath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'product' . DS . $fileName;
            $newPath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'product' . DS . $dir . DS . $fileName;
        }else{
            $width=81;
            $height=80;
        }
        
        if ($width != '' && $height != '') {
            if (file_exists($newPath)) {
                unlink($newPath);
            }

            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) 
            {
                $imageObj = new Varien_Image($basePath);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepFrame(false);
                $imageObj->keepTransparency(true);
                $imageObj->quality(100);
                $imageObj->resize($width, $height);
                $imageObj->save($newPath);
            }

            $resizedURL = Mage::getBaseUrl('media').$dir."/".$fileName;
        } else {
             $resizedURL = $imageURL;
        } 
        
        return $resizedURL;
    }
}
