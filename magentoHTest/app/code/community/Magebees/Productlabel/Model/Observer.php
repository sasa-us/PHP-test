<?php
/***************************************************************************
	@extension	: Product Label.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class Magebees_Productlabel_Model_Observer
{
 
    public function adminSystemConfigChangedSection($observer)
    {
        //return $this;
        $labelconfig = Mage::getStoreConfig('productlabel/labelsize');
        $fieldName = array('category', 'product');
        //get all resized image and unlink 
        foreach($fieldName as $field){
            if($field=="category")
            {
                $width=$labelconfig['width_category'];
                $height=$labelconfig['height_category'];
            }
            elseif($field=="product")
            {
                $width=$labelconfig['width_product'];
                $height=$labelconfig['height_product'];
            }

            $dir = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "custom" . DS . $field . DS . "resized";
            
            if (is_dir($dir)) {
                foreach(glob($dir . '/*') as $file) {
                    unlink($file); 
                }
            }
            
            $path_to_image_dir = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "custom". DS . $field;
            
            if ($dh = opendir($path_to_image_dir))
            {
                while (($file = readdir($dh)) !== false)
                {
                    if (is_file($path_to_image_dir.'/'.$file))
                    {
                        $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "custom". DS . $field . DS . $file;
                    
                        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "custom". DS . $field . DS . "resized". DS . $file;
            
                            //if image has already resized then just return URL
                            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                                $imageObj = new Varien_Image($basePath);
                                $imageObj->constrainOnly(TRUE);
                                $imageObj->keepAspectRatio(true);
                                $imageObj->keepFrame(false);
                                $imageObj->keepTransparency(true);
                                $imageObj->quality(100);
                                $imageObj->resize($width, $height);
                                $imageObj->save($newPath);
                            }
                    }
                }
                
                closedir($dh);
            }
        }
        
    }
}
     

