<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Block_Adminhtml_EmailCapture_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('emailcaptureGrid');
        $this->setDefaultSort('emailcapture_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('listrak/emailcapture')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('emailcapture_id', array(
            'header'    => Mage::helper('remarketing')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'emailcapture_id',
        ));
 
        $this->addColumn('page', array(
            'header'    => Mage::helper('remarketing')->__('Page'),
            'align'     =>'left',
            'index'     => 'page',
        ));
 
        $this->addColumn('field_id', array(
            'header'    => Mage::helper('remarketing')->__('Field ID'),
            'align'     => 'left',
            'index'     => 'field_id',
        ));
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
 
 
}