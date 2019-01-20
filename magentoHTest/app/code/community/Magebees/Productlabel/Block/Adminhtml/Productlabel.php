<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_productlabel';
        $this->_blockGroup = 'productlabel';
        $this->_headerText = Mage::helper('productlabel')->__('Manage Label');
        $this->_addButtonLabel = Mage::helper('productlabel')->__('Add New Label');
        parent::__construct();
    }
}
