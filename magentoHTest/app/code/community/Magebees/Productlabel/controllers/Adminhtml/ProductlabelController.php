<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Adminhtml_ProductlabelController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() 
    {
        $this->loadLayout()->_setActiveMenu('cws');
        return $this;
    }
    
    public function indexAction() 
    {
        $this->_title($this->__('CWS Extensions'))->_title($this->__('Product Label'))->_title($this->__('Manage Labels'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction() 
    {
        
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('productlabel/productlabel')->load($id);
        $this->_title($this->__('CWS Extensions'))
                ->_title($this->__('Product Label'))
                ->_title($this->__('Manage Labels'));
        if($id){
            $this->_title($this->__($model->getTitle()));
        }else{
            $this->_title($this->__('Add Label'));
        }
        
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            
            Mage::register('productlabel_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('cws');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit'))
                ->_addLeft($this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productlabel')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
    
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
         $id = $this->getRequest()->getParam('id');
        
        if ($data) {
            $model = Mage::getModel('productlabel/productlabel');    
                    
            try {
                $data = $this->_filterDateTime($data, array('from_date', 'to_date'));
                $data = $this->_filterPicture($data);//for rename and move to specific directory
                $model->setData($data)->setId($id);
                $validateResult = $model->validateData(new Varien_Object($data));//for validate start ,end date and time duration
                if ($validateResult !== true) {
                    foreach ($validateResult as $errorMessage) {
                        $this->_getSession()->addError($errorMessage);
                    }

                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                        
                $model->save();
                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productlabel')->__('Label was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productlabel')->__('Unable to find label to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productlabel/productlabel');
                 
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                     
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Label was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }

        $this->_redirect('*/*/');
    }
    
    protected function _filterPicture($data)
    {
        $fieldName = array('cat_image', 'prod_image');
        
        foreach ($fieldName as $field){
            if (isset($_FILES[$field]['name']) and (file_exists($_FILES[$field]['tmp_name']))) {
                try {
                    if($field=="cat_image"){
                        $path =  Mage::getBaseDir('media') . DS . 'custom' . DS . 'category' . DS;
                    }

                    if($field=="prod_image"){
                        $path =  Mage::getBaseDir('media') . DS . 'custom' . DS . 'product' . DS;
                    } 
                    
                    //$path = Mage::getBaseDir('media') . DS . 'custom' . DS . 'upload' . DS;
                    $temp = explode(".", $_FILES[$field]['name']);
                    $newfilename = rand(1, 99999). '.' .end($temp);
                    
                    $uploader = new Varien_File_Uploader($field);
                    
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png')); // or pdf or anything
                    $uploader->setAllowRenameFiles(false);             
                    $uploader->setFilesDispersion(false);
                    //$fileName = $_FILES[$fieldName]['name'];
                    $fileName = $newfilename;
                    $uploader->save($path, $fileName);
                    Mage::helper('productlabel')->resizeImg($fileName, $field);
                    $data[$field] = $fileName;
                }catch (Exception $e) {
                    $data[$field] = '';
                    $this->_getSession()->addError(
                        Mage::helper('productlabel')->__('Disallowed file type.')
                    );
                    $this->_getSession()->addError(
                        Mage::helper('productlabel')->__($e->getMessage())
                    );
                }
            }
            else {
                if (isset($data[$field]['delete']) && $data[$field]['delete'] == 1) {
                    $data[$field] = '';
                } else {
                    unset($data[$field]);
                }
            }
        }
        
        return $data;
    }
    
    public function attroptionsAction()
    {
        $result = '<input id="attr_value" name="attr_value" value="" class="input-text" type="text" />';
        
        $code = $this->getRequest()->getParam('code');
        if (!$code){
            $this->getResponse()->setBody($result);
            return;
        }
        
        $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($code);
        /* echo "<pre>";
		print_R(get_class_methods(get_class($attribute->getFrontend()->getSelectOptions())));
		print_R($attribute->getFrontend()->getSelectOptions());exit; */
        if (!$attribute){
            $this->getResponse()->setBody($result);
            return;            
        }

         $dropdowns = array('select', 'multiselect', 'boolean','textarea');
        if (!in_array($attribute->getFrontendInput(), $dropdowns)){
            $this->getResponse()->setBody($result);
            return;            
        }
        
        if($attribute->getFrontendInput()=='textarea'){
            $result = '<textarea id="attr_value" name="attr_value"></textarea>';
            $this->getResponse()->setBody($result);
            return;
        }
        
        if($attribute->getFrontendInput()=='boolean')
        {
            $options[0]['label'] = 'Yes';
            $options[0]['value'] = '1';
            $options[1]['label'] = 'No';
            $options[1]['value'] = '0';
        }
        else{ 
            $options = $attribute->getFrontend()->getSelectOptions();
        }
                
        $result = '<select id="attr_value" name="attr_value" class="select">';
        foreach ($options as $option){
            if(is_array($option['value'])){
                foreach($option as $opt){
                if(is_array($opt)){
                    foreach($opt as $opt){
                        if(isset($opt['value'])){
                            $result .= '<option value="'.$opt['value'].'">'.$opt['value'].'</option>';
                        }
                    }
                }
                }
            }else{
                $result .= '<option value="'.$option['value'].'">'.$option['label'].'</option>';      
            }
        }

        $result .= '</select>';    
        
        $this->getResponse()->setBody($result);
    }
    
    public function categoriesAction()
    {
        $this->_initcategory();
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function categoriesJsonAction()
    {
    
        $this->_initcategory();
        $this->getResponse()->setBody( 
            $this->getLayout()->createBlock('productlabel/adminhtml_productlabel_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }
    
    protected function _initcategory()
    {
        
        $categoryId  = $this->getRequest()->getParam('id');
        $category    = Mage::getModel('productlabel/productlabel');
      
            if ($categoryId) {
                $category->load($categoryId);
            }

            Mage::register('productlabel_data', $category);
        return $category;
    }
    
    public function massDeleteAction() 
    {
        $productlabelIds = $this->getRequest()->getParam('productlabel');
        if(!is_array($productlabelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productlabelIds as $id) {
                    $productlabel = Mage::getModel('productlabel/productlabel')->load($id);
                    $productlabel->delete();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($productlabelIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    
    public function massStatusAction()
    {
        $ids = $this->getRequest()->getParam('productlabel');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $productlabelModel = Mage::getModel('productlabel/productlabel')
                        ->load($id)
                        ->setIsActive($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($ids))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('productlabel/adminhtml_productlabel_grid')->toHtml()
        );
    }
    
	protected function _isAllowed()
    {
        return true;
    }
}
