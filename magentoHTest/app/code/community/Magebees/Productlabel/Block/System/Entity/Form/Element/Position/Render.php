<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_System_Entity_Form_Element_Position_Render extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Path to element template
     */
    const TEMPLATE_PATH = 'productlabel/form/element/render/position.phtml';

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate(self::TEMPLATE_PATH);
    }

    public function getName()
    {
        return $this->getData('name') ? $this->getData('name') : $this->getData('html_id');
    }
    
}
