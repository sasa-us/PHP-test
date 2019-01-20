<?php
/***************************************************************************
 Extension Name	: Product Label
 Extension URL	: http://www.magebees.com/magento-product-label-extension.html
 Copyright		: Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class Magebees_Productlabel_Block_Adminhtml_Productlabel_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productlabelGrid');
        $this->setDefaultSort('label_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()    
    {
        $collection = Mage::getModel('productlabel/productlabel')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'label_id', array(
            'header'    => Mage::helper('productlabel')->__('ID'),
            'align'     => 'right',
            'width'        => '50px',
            'index'     => 'label_id',
            )
        );
        
        $this->addColumn(
            'title', array(
            'header'    => Mage::helper('productlabel')->__('Title'),
            'index'        => 'title',
            )
        );
        
        $this->addColumn(
            'prod_image', array(
            'header'    => Mage::helper('productlabel')->__('Product Page Label'),
            'index'        => 'prod_image',
            'align'        => 'center',
            'frame_callback' => array($this, 'pro_image')
            )
        );
        
        $this->addColumn(
            'prod_text', array(
            'header'    => Mage::helper('productlabel')->__('Product Page Text'),
            'index'        => 'prod_text',
            )
        );
        
        $this->addColumn(
            'cat_image', array(
            'header'    => Mage::helper('productlabel')->__('Category Page Label'),
            'index'        => 'cat_image',
            'align'        => 'center',
            'frame_callback' => array($this, 'cat_image')
            )
        );
        
        $this->addColumn(
            'cat_text', array(
            'header'    => Mage::helper('productlabel')->__('Category Page Text'),
            'index'        => 'cat_text',
            )
        );
        
        $this->addColumn(
            'sort_order', array(
            'header'    => Mage::helper('productlabel')->__('Priority'),
            'align'     => 'right',
            'width'        => '50px',
            'index'     => 'sort_order',
            )
        );
        
        $this->addColumn(
            'is_active', array(
            'header'    => Mage::helper('productlabel')->__('Status'),
            'index'        => 'is_active',
            'type'      => 'options',
            'frame_callback' => array($this, 'decorateStatus'),
            'options'   => array('1' => Mage::helper('adminhtml')->__('Active'), '0' => Mage::helper('adminhtml')->__('Inactive')),
            )
        );
        
        
        //$this->addExportType('*/*/exportCsv', Mage::helper('productlabel')->__('CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('productlabel')->__('XML'));
      
        return parent::_prepareColumns();
    }
    
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('label_id');
        $this->getMassactionBlock()->setFormFieldName('productlabel');

        $this->getMassactionBlock()->addItem(
            'delete', array(
             'label'    => Mage::helper('productlabel')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('productlabel')->__('Are you sure?')
            )
        );
        
        $status = array(
            array('value' => 1, 'label'=>Mage::helper('productlabel')->__('Active')),
            array('value' => 0, 'label'=>Mage::helper('productlabel')->__('Inactive')),
        );

        array_unshift($status, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem(
            'status', array(
             'label'=> Mage::helper('productlabel')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('productlabel')->__('Status'),
                         'values' => $status
                     )
             )
            )
        );
       
        return $this;
    }

    public function decorateStatus($value, $row, $column, $isExport)
    {
        if ($value=='Inactive') {
            $cell = '<span class="grid-severity-minor"><span>'.$value.'</span></span>';
        } else {
            $cell = '<span class="grid-severity-notice"><span>'.$value.'</span></span>';
        }

        return $cell;
    }
    
    public function cat_image($value)
    {
        if($value){
            $width = 70;
            $height = 70;
            return "<img src='".Mage::getBaseUrl('media').'custom/category/'.$value."' width=".$width." height=".$height." alt='No Image Avialable'/>";
        }else{
            return "No Image Avialable";
        }
    }
    
    public function pro_image($value)
    {
        if($value){
            $width = 70;
            $height = 70;
            return "<img src='".Mage::getBaseUrl('media').'custom/product/'.$value."' width=".$width." height=".$height." alt='No Image Avialable'/>";
        }else{
            return "No Image Avialable";
        }
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}
