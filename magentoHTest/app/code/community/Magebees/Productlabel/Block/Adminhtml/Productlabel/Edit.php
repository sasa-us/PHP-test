<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'productlabel';
        $this->_controller = 'adminhtml_productlabel';
        $this->_updateButton('save', 'label', 'Save');
        $this->_updateButton('delete', 'label', 'Delete');
        $this->_addButton(
            'save_and_continue', array(
             'label' => Mage::helper('productlabel')->__('Save And Continue Edit'),
             'onclick' => 'saveAndContinueEdit()',
             'class' => 'save' 
            ), -100
        );
         $label_data = Mage::registry('productlabel_data');
         $include_sku = $label_data['include_sku'];
         $this->_formScripts[] = "
            function saveAndContinueEdit(){
				editForm.submit($('edit_form').action + 'back/edit/');
            }
			 
			onload = function()
			{
				document.getElementById('include_sku').value = '".$include_sku."';
			}
			
			function showOptions(sel) {
            new Ajax.Request('" . $this->getUrl('*/*/attroptions', array('isAjax'=>true)) ."', {
                parameters: {code : sel.value},
                onSuccess: function(transport) {
                    $('attr_value').up().update(transport.responseText);
                }
            });
        }
            ";
        $this->setId('productlabel_edit');
    }
    
    public function getHeaderText()
    {
        if(Mage::registry('productlabel_data') && Mage::registry('productlabel_data')->getId())
        {
            return 'Edit Label '.$this->htmlEscape(Mage::registry('productlabel_data')->getTitle()).'<br/>';
        }
        else
        {
            return 'Add Label';
        }
    }    
}
