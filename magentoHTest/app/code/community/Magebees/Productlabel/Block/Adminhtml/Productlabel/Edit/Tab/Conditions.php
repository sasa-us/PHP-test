<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel_Edit_Tab_Conditions extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $model = Mage::registry('productlabel_data');
        $this->setForm($form);
        $helper =Mage::helper('productlabel');
        
        //Attribute
        $fieldset = $form->addFieldset('attribute_form', array('legend'=>Mage::helper('productlabel')->__('Attributes')));
        
        $fieldset->addField(
            'include_cat', 'hidden', array(
            'name'      => 'include_cat',
            )
        );
        
        $fieldset->addField(
            'attr_code', 'select', array(
            'label'     => $helper->__('Has attribute'),
            'name'      => 'attr_code',
            'values'    => $this->getAttributes(),
            'onchange'  => 'showOptions(this)',
            'note'      => $helper->__('If you do not see the label, please make sure the attribute properties `Visible on Product View Page on Front-end`, `Used in Product Listing` are set to `Yes`'),
            )
        );
        
        //for get selected attribute
        $attributeCode = $model->getData('attr_code');
        if (('' != $attributeCode) && ($attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($attributeCode))) {
            $dropdowns = array('select', 'multiselect', 'boolean');
            if (in_array($attribute->getFrontendInput(), $dropdowns)) {
                $options = $attribute->getFrontend()->getSelectOptions();
                $fieldset->addField(
                    'attr_value', 'select', array(
                    'label'     => $helper->__('Attribute value is'),
                    'name'      => 'attr_value',
                    'values'    => $options,
                    )
                );
            }elseif($attribute->getFrontendInput()=='textarea'){ 
                $fieldset->addField(
                    'attr_value', 'textarea', array(
                    'label'     => $helper->__('Attribute value is'),
                    'name'      => 'attr_value',
                    )
                );
            }else {
                $fieldset->addField(
                    'attr_value', 'text', array(
                    'label'     => $helper->__('Attribute value is'),
                    'name'      => 'attr_value',
                    'note'      => $helper->__('Please add value in same format which is save in database.Like For Price add 10.00 .'),
                    )
                );
            }
        }
        else{
            $fieldset->addField(
                'attr_value', 'text', array(
                'label'     => $helper->__('Attribute value is'),
                'name'      => 'attr_value',
                 'note'      => $helper->__('Please add value in same format which is save in database.Like For Price add 10.00 .'),
                )
            );
        }
          
        //Is New or Is On Sale
        $fieldset = $form->addFieldset('new_form', array('legend'=>Mage::helper('productlabel')->__('Is New or Is On Sale')));
        $fieldset->addField(
            'is_new', 'select', array(
            'label'     => $helper->__('Is New'),
            'name'      => 'is_new',
            'values'    => array(
                0 => $helper->__('-- Please Select --'), 
                1 => $helper->__('No'), 
                2 => $helper->__('Yes'), 
             ),
            )
        );
        
        $fieldset->addField(
            'is_sale', 'select', array(
            'label'     => $helper->__('Is on Sale'),
            'name'      => 'is_sale',
            'values'    => array(
                0 => $helper->__('-- Please Select --'), 
                1 => $helper->__('No'), 
                2 => $helper->__('Yes'), 
             ),
            )
        );
        
        //Date Range
        $fieldset = $form->addFieldset('date_range_form', array('legend'=>Mage::helper('productlabel')->__('Date Range')));
        
        $date_enabled = $fieldset->addField(
            'date_enabled', 'select', array(
            'label'     => $helper->__('Use Date Range'),
            'name'      => 'date_enabled',
            'values'    => array(
                0 => $helper->__('No'), 
                1 => $helper->__('Yes'), 
             ),
            )
        );
        
        $note= '<a target="_blank" href="'. $this->getUrl('adminhtml/system_config/edit/general/') .'">'. $this->__('click here') .'</a>';
        $current_timezone = Mage::app()->getStore()->getConfig('general/locale/timezone');
        $current_datetime = Mage::getModel('core/date')->date('Y-m-d H:i:s');
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        
        
        $from_date = $fieldset->addField(
            'from_date', 'date', array(
            'name' => 'from_date',
            'label' => $helper->__('From Date & Time'),
            'title' => $helper->__('From Date & Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'class' => 'validate-date',
            'time' => true,
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso
            )
        );
        
        $to_date = $fieldset->addField(
            'to_date', 'date', array(
            'name' => 'to_date',
            'label' => $helper->__('To Date & Time'),
            'title' => $helper->__('To Date & Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'class' => 'validate-date',
            'time' => true,
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
            'note'      =>"<p class='note'>Current Timezone : ".$current_timezone."<br>Current Date Time : ".$current_datetime."<br>If you want to change timezone ".$note."</p>"
            )
        );
        
        //Price Range
        $fieldset = $form->addFieldset('price_range_form', array('legend'=>Mage::helper('productlabel')->__('Price Range')));
        
        $price_enabled = $fieldset->addField(
            'price_enabled', 'select', array(
            'label'     => $helper->__('Use Price Range'),
            'title'     => $helper->__('Use Price Range'),
            'name'      => 'price_enabled',
            'options'   => array(           
                '0' => $helper->__('No'),
                '1' => $helper->__('Yes'),
            ),
            )
        );
        
        $by_price = $fieldset->addField(
            'by_price', 'select', array(
            'label'     => $helper->__('By Price'),
            'title'     => $helper->__('By Price'),
            'name'      => 'by_price',
            'options'   => array(           
                '0' => $helper->__('Base Price'),
                '1' => $helper->__('Special Price'),
                '2' => $helper->__('Final Price'),
                '3' => $helper->__('Final Price Incl Tax'),
            ),
            )
        );
        
        $from_price = $fieldset->addField(
            'from_price', 'text', array(
            'name'   => 'from_price',
            'label'  => $helper->__('From Price'),
            'title'  => $helper->__('From Price'),
            'class'     => 'validate-zero-or-greater',
            )
        );
        
        $to_price = $fieldset->addField(
            'to_price', 'text', array(
            'name'   => 'to_price',
            'label'  => $helper->__('To Price'),
            'title'  => $helper->__('To Price'),
            'class'     => 'validate-zero-or-greater',
            )
        );
        
        //Stock Status
        $fieldset = $form->addFieldset('stock_form', array('legend'=>Mage::helper('productlabel')->__('Stock Status')));
        
        $fieldset->addField(
            'stock_status', 'select', array(
            'label'     => $helper->__('Status'),
            'name'      => 'stock_status',
            'values'    => array(
                '0' => $helper->__('-- Please Select --'), 
                '1' => $helper->__('In Stock'), 
                '2' => $helper->__('Out of Stock'), 
             ),
            )
        );
        
        $form->setValues($model->getData());
        
         $this->setChild(
             'form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
             ->addFieldMap($to_price->getHtmlId(), $to_price->getName())
             ->addFieldMap($from_price->getHtmlId(), $from_price->getName())
             ->addFieldMap($by_price->getHtmlId(), $by_price->getName())
             ->addFieldMap($price_enabled->getHtmlId(), $price_enabled->getName())
             ->addFieldMap($to_date->getHtmlId(), $to_date->getName())
             ->addFieldMap($from_date->getHtmlId(), $from_date->getName())
             ->addFieldMap($date_enabled->getHtmlId(), $date_enabled->getName())
             ->addFieldDependence(
                 $to_date->getName(),
                 $date_enabled->getName(),
                 1
             )
             ->addFieldDependence(
                 $from_date->getName(),
                 $date_enabled->getName(),
                 1
             )
             ->addFieldDependence(
                 $to_price->getName(),
                 $price_enabled->getName(),
                 1
             )
             ->addFieldDependence(
                 $from_price->getName(),
                 $price_enabled->getName(),
                 1
             )
             ->addFieldDependence(
                 $by_price->getName(),
                 $price_enabled->getName(),
                 1
             )
         );
            
        return parent::_prepareForm();
    }
    
    //get all attribute values and return array key as code , value as frontend label
    protected function getAttributes()
    {
        $attr_collection = Mage::getResourceModel('eav/entity_attribute_collection')
                        ->setItemObjectClass('catalog/resource_eav_attribute')
                        ->setEntityTypeFilter(Mage::getResourceModel('catalog/product')->getTypeId())
                        ->setOrder('frontend_label', 'ASC');
        
        $options = array(''=>'Select Attribute');
        foreach ($attr_collection as $attr){
            if($attr->getUsedInProductListing() || $attr->getIsVisibleOnFront()){
                $label = $attr->getFrontendLabel();
                if ($label){ 
                    $options[$attr->getAttributeCode()] = $label;
                }
            }
        }

        return $options;
    }
    
}
