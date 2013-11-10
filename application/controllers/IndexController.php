<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    	if ($this->_request->isPost()) {
    		$data = $this->_request->getPost();
    		Zend_Debug::dump($data);
    	}
    	
    }
    
    public function addfileAction()
    {
    	// action body
       
    }

}

