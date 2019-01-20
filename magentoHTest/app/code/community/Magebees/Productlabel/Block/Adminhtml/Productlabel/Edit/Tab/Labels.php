<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel_Edit_Tab_Labels extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $model = Mage::registry('productlabel_data');
        /* category page */
        $fieldset = $form->addFieldset(
            'category_page_label', array(
            'legend' => Mage::helper('productlabel')->__('Category Page Label')
                )
        );
        //for get image url 
        $fieldset->addType('image', 'Magebees_Productlabel_Block_Adminhtml_Productlabel_Helper_Category');
        
        $renderer = new Magebees_Productlabel_Block_System_Config_Form_Element_Position();
        $values = Mage::getModel('productlabel/system_config_source_position')->toOptionArray();
        
        $fieldset->addField(
            'cat_position', 'select', array(
            'name' => 'cat_position',
            'label' => Mage::helper('productlabel')->__('Position'),
            'title' => Mage::helper('productlabel')->__('Position'),
            'values' => $values,
            'value' => ((isset($values[0]['value'])) ? ($values[0]['value']) : ''),
            )
        )->setRenderer($renderer);
        
        $fieldset->addField(
            'cat_image', 'image', array(
            'name' => 'cat_image',
            'label' => Mage::helper('productlabel')->__('Image'),
            'note'      => Mage::helper('productlabel')->__('Recommended label size 80px X 80px'),
            )
        );
        
        $fieldset->addField(
            'cat_text', 'text', array(
            'name' => 'cat_text',
            'label' => Mage::helper('productlabel')->__('Text'),
            'after_element_html' => '<p class="note"><span>'
            . Mage::helper('productlabel')
                    ->__('Write text here for display on label.')
            . '</span></p>',
            )
        );
        
        $fieldset->addField(
            'cat_text_color', 'text', array(
            'label' => Mage::helper('productlabel')->__('Choose Text Color'),
            'name' => 'cat_text_color',
            'class' => 'color',
            )
        );
        
        $fieldset->addField(
            'cat_text_size', 'text', array(
            'label' => Mage::helper('productlabel')->__('Text Font Size'),
            'name' => 'cat_text_size',
            'note'      => Mage::helper('productlabel')->__('Add font size in pixels'),
            )
        );

        /* product page */
    
        $fieldset = $form->addFieldset(
            'product_page_label', array(
            'legend' => Mage::helper('productlabel')->__('Product Page Label')
                )
        );
        
        $fieldset->addType('image', 'Magebees_Productlabel_Block_Adminhtml_Productlabel_Helper_Product');
        
        $renderer = new Magebees_Productlabel_Block_System_Config_Form_Element_Position();
        $values = Mage::getModel('productlabel/system_config_source_position')->toOptionArray();

        $fieldset->addField(
            'prod_position', 'select', array(
            'name' => 'prod_position',
            'label' => Mage::helper('productlabel')->__('Position'),
            'title' => Mage::helper('productlabel')->__('Position'),
            'values' => $values,
            'value' => ((isset($values[0]['value'])) ? ($values[0]['value']) : ''),
            )
        )->setRenderer($renderer);

        $fieldset->addField(
            'prod_image', 'image', array(
            'name' => 'prod_image',
            'label' => Mage::helper('productlabel')->__('Image'),
            'note'      => Mage::helper('productlabel')->__('Recommended label size 110px X 110px'),
            )
        );

        $fieldset->addField(
            'prod_text', 'text', array(
            'name' => 'prod_text',
            'label' => Mage::helper('productlabel')->__('Text'),
            'after_element_html' => '<p class="note"><span>'
            . Mage::helper('productlabel')
                    ->__('Write text here for display on label.')
            . '</span></p>',
            )
        );
        
        $fieldset->addField(
            'prod_text_color', 'text', array(
            'label' => Mage::helper('productlabel')->__('Choose Text Color'),
            'name' => 'prod_text_color',
            'class' => 'color',
            )
        );
        
        $fieldset->addField(
            'prod_text_size', 'text', array(
            'label' => Mage::helper('productlabel')->__('Text Font Size'),
            'name' => 'prod_text_size',
            'note'      => Mage::helper('productlabel')->__('Add font size in pixels'),
            )
        );

        $form->setValues($model->getData());
        //$form->setUseContainer(true);
         /* if ($model->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        } */
 
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
