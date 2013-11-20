<?php

class ViewerController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    	//Disabling the layout
    	$this->_helper->layout->disableLayout();
    }


}

