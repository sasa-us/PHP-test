<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_System_Entity_Form_Element_Position extends Varien_Data_Form_Element_Select
{
    /**
     * Retrives element's html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function getElementHtml()
    {
        $select = new Magebees_Productlabel_Block_System_Entity_Form_Element_Position_Render($this->getData());
        $select->setLayout(Mage::app()->getLayout());

        if (Mage::registry('current_product')){            
            $select->setData('name', 'product['.$select->getName().']');
        }

        $html = '';
        $html .= $select->toHtml();

        $html.= $this->getAfterElementHtml();
        return $html;
    }
}