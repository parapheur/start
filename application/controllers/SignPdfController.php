<?php

class SignPDFController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
    	$this->_helper->layout->disableLayout();
    	$canvas = $this->_getParam('colors_sketch', false);
    	/*if( != null)
    	{
    		$this->_helper->redirector('showfile','ShowPdf');
    	}*/
    }


}

