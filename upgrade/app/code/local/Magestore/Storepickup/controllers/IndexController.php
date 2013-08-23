<?php
class Magestore_Storepickup_IndexController extends Mage_Core_Controller_Front_Action
{

    protected function _getCoreSession(){
		return Mage::getSingleton('core/session');
    }
    public function indexAction()
    {					
            if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){return;}
            $this->loadLayout();
            $this->getLayout()
                            ->getBlock('head')
                            ->setTitle(Mage::helper('core')->__('Our Stores'));
            $this->renderLayout();
    }

    public function changestoreAction()
    {	
            $is_storepickup = $this->getRequest()->getParam('is_storepickup');

            if($is_storepickup)
            {
                    $data['is_storepickup'] = $is_storepickup;
                    Mage::getSingleton('checkout/session')->setData('storepickup_session',$data);	
                    return;
            }
            $data = Mage::getSingleton('checkout/session')->getData('storepickup_session');

            //storepickup
            $data['store_id'] = $this->getRequest()->getParam('store_id');

            Mage::getSingleton('checkout/session')->setData('storepickup_session',$data);
    }

    public function changedateAction()
    {
            try{
                    $shipping_date = $this->getRequest()->getParam('shipping_date');
                    $store_id = $this->getRequest()->getParam('store_id');

                    $storepickup = Mage::getSingleton('checkout/session')->getData('storepickup_session');
                    $storepickup['date'] = $shipping_date;
                    Mage::getSingleton('checkout/session')->setData('storepickup_session',$storepickup);

                    $html_select = Mage::helper('storepickup')->getTimeSelectHTML($shipping_date,$store_id);
            $this->getResponse()->setBody($html_select);		
                    }catch(Exception $e){
                    Mage::getSingleton('checkout/session')->setData('myerror',$e->getMessage());
            }
    }

    public function changetimeAction()
    {
            $shipping_time = $this->getRequest()->getParam('shipping_time');

            $storepickup = Mage::getSingleton('checkout/session')->getData('storepickup_session');

            $storepickup['time'] = $shipping_time;

            Mage::getSingleton('checkout/session')->setData('storepickup_session',$storepickup);		
    }

    public function savecontactAction(){
            $id  = $this->getRequest()->getParam('id');
            //var_dump($this->_redirect('*/*/index/'));die();
            $mod = Mage::getModel('storepickup/message');
            $coreSession = $this->_getCoreSession();
            $captchaCode = $coreSession->getData('captcha_code'.$this->getRequest()->getParam('id'));
            $data = $this->getRequest()->getPost();                          
            $data['store_id'] = $id;
            if ($captchaCode != $data['captcha']){
                Mage::getSingleton('core/session')->setPickupFormData($data);                
                $coreSession->addError(Mage::helper('storepickup')->__('Please enter correct verification code!'));
                return  $this->_redirect('*/*/index/', array('viewstore' => $id));   
            }
            else{
                 Mage::getSingleton('core/session')->setPickupFormData(null); 
            }
            $mod->setData($data);             
            $datatime = $this->_filterDateTime(now());            
            $mod->setDateSent($datatime);
            $mod->save();
            Mage::helper('storepickup/email')->sendEmailtoAdmin($mod->getId());
            Mage::helper('storepickup/email')->sendEmailtoStoreOwner($mod->getId(), $id);
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('storepickup')->__('Message has been sent to store owner successfully!'));
            $this->_redirect('*/*/index/', array('viewstore' => $id));     
    }

    public function imagecaptchaAction(){
        require_once(Mage::getBaseDir('lib') . DS .'captcha'. DS .'class.simplecaptcha.php');
        $config['BackgroundImage'] = Mage::getBaseDir('lib') . DS .'captcha'. DS . "white.png";
        $config['BackgroundColor'] = "FF0000";
        $config['Height']=30;
        $config['Width']=100;
        $config['Font_Size']=23;
        $config['Font']= Mage::getBaseDir('lib') . DS .'captcha'. DS . "ARLRDBD.TTF";
        $config['TextMinimumAngle']=15;
        $config['TextMaximumAngle']=30;
        $config['TextColor']='2B519A';
        $config['TextLength']=4;
        $config['Transparency']=80;
        $captcha = new SimpleCaptcha($config);
        $this->_getCoreSession()->setData('captcha_code'.$this->getRequest()->getParam('id'),$captcha->Code);
    }

    public function refreshcaptchaAction(){
        $result = Mage::getModel('core/url')->getUrl('*/*/imageCaptcha',array(
			'id' => $this->getRequest()->getParam('id'),
			'time'	=> time(),
		));
        echo $result;
    }
}