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
    	
    	//Fetch data from database to list PDF files
    	$courrier = new Application_Model_DbTable_Courrier;
    	$courrierRows = $courrier->fetchAll();
    	$this->view->courrier = $courrierRows;
    	
    	//Count how many documents we have to display in the index
    	$rowCount = count($courrierRows);
    	$this->view->courrierRows = $rowCount;
    	
    }
    
    public function addfileAction()
    {
    	// action body
       
    }

}

