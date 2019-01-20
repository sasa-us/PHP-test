<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productlabel_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Label Information');
    }
    protected function _beforeToHtml()
    {
        $this->addTab(
            'general_section', array(
            'label'     => Mage::helper('productlabel')->__('General'),
            'title'     => Mage::helper('productlabel')->__('General'),
            'content'   => $this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit_tab_general')->toHtml(),
            )
        );
        
        $this->addTab(
            'labels_section', array(
            'label'     => Mage::helper('productlabel')->__('Labels'),
            'title'     => Mage::helper('productlabel')->__('Labels'),
            'content'   => $this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit_tab_labels')->toHtml(),
            )
        );
        
        $this->addTab(
            'product_section', array(
            'label'     => Mage::helper('productlabel')->__('Products'),
            'title'     => Mage::helper('productlabel')->__('Products'),
            'content'   => $this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit_tab_products')->toHtml(),
            )
        );
        
        $this->addTab(
            'category_section', array(
            'label'     => Mage::helper('productlabel')->__('Categories'),
            'title'     => Mage::helper('productlabel')->__('Categories'),
            'content'   => $this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit_tab_categories')->toHtml(),
            )
        );
        
        $this->addTab(
            'conditions_section', array(
            'label'     => Mage::helper('productlabel')->__('Conditions'),
            'title'     => Mage::helper('productlabel')->__('Conditions'),
            'content'   => $this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit_tab_conditions')->toHtml(),
            )
        );
        
        return parent::_beforeToHtml();
    }
}
