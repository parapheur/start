<?php

class ViewerController extends Zend_Controller_Action
{

	public function init()
    {
    	//We get the ID of the document we have to display from the indexController
    	$request = $this->getRequest();
    	$this->id_document = $request->getParam('COURRIER_ID');

    }

    public function indexAction()
    {
        // action body
    	//Disabling the layout
    	$form = new Application_Form_Passparam();
    	$formData = $form->getValues();
    	
    	$sessioniddoc = new Zend_Session_Namespace('sessioniddoc');
    	$id_doc = $sessioniddoc->id;
    	
    	$this->_helper->layout->disableLayout();
    	$this->view->pdfurl='../pdf/TD-1.pdf';
    	$this->view->id_doc=$id_doc;
    }


}

