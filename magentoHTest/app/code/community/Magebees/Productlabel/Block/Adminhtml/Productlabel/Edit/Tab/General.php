<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('product_form', array('legend'=>Mage::helper('productlabel')->__('General')));

        $fieldset->addField(
            'title', 'text', array(
            'label'     => Mage::helper('productlabel')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
            )
        );

        $fieldset->addField(
            'is_active', 'select', array(
            'label'    => Mage::helper('productlabel')->__('Status'),
            'title' => Mage::helper('productlabel')->__('Status'),
            'name'     => 'is_active',
            'options' => array(
                '1' => Mage::helper('productlabel')->__('Active'),
                '0' => Mage::helper('productlabel')->__('Inactive'),
            ),
            )
        );
        
        $fieldset->addField(
            'hide', 'select', array(
            'label'    => Mage::helper('productlabel')->__('Hide if high sort order label already applied'),
            'title' => Mage::helper('productlabel')->__('Hide if high sort order label already applied'),
            'name'     => 'hide',
            'options' => array(
                '1' => Mage::helper('productlabel')->__('Yes'),
                '0' => Mage::helper('productlabel')->__('No'),
            ),
            )
        );
        
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'stores', 'multiselect', array(
                'name'    => 'stores[]',
                'label' => Mage::helper('productlabel')->__('Store View'),
                'title' => Mage::helper('productlabel')->__('Store View'),
                'required' => true,
                'values'=> Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
                )
            );
        } else {
            $fieldset->addField(
                'stores', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId(),
                )
            );
        }
                
        $fieldset->addField(
            'customer_group_ids', 'multiselect', array(
            'name' => 'customer_group_ids[]',
            'label' => Mage::helper('productlabel')->__('Customer Groups'),
            'title' => Mage::helper('productlabel')->__('Customer Groups'),
            'required' => true,
            'values' => Mage::getResourceModel('customer/group_collection')->toOptionArray()
            )
        );
       
        $fieldset->addField(
            'sort_order', 'text', array(
            'name' => 'sort_order',
            'class'=> 'validate-digits',
            'label' => Mage::helper('productlabel')->__('Priority'),
            'note'      => Mage::helper('productlabel')->__('0 = High Priority'),
            )
        );
            

        if (Mage::getSingleton('adminhtml/session')->getProductlabelData())
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getProductlabelData());
            Mage::getSingleton('adminhtml/session')->setProductlabelData(null);
        } elseif (Mage::registry('productlabel_data')) {
            $form->setValues(Mage::registry('productlabel_data')->getData());
        }

        return parent::_prepareForm();
    }
}
