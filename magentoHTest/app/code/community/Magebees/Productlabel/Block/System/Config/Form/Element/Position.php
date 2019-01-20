<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_System_Config_Form_Element_Position extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Change field renderer
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $newElement = new Magebees_Productlabel_Block_System_Entity_Form_Element_Position($element->getData());
        return $newElement->getElementHtml();
    }

}