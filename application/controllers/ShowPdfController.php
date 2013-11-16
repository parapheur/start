<?php

/*
 * File : ShowPdfController.php
* Author : Hina Tufail
* Created : 19/10/2013
* Modified : 16/11/2013
* 1.1 :  Hina Tufail - creation
* 1.2 : Mathilde de l'Hermuzière - modification
* 1.3 : Hina Tufail - modification
* 1.4 : Clément Mouraud - modification
* 1.5 : Mathilde de l'Hermuzière - modification
* 1.6 : Hina Tufail - modification
* 1.7 : Clément Mouraud - modification
* 1.8 : Hina Tufail - modification
* 1.9 : Hina Tufail - modification
*
* Controller that controls views for doing action on a PDF document
*
* Projet parapheur 2014
*/

class ShowPdfController extends Zend_Controller_Action
{

    public function init()
    {
    }

    //Action that displays a PDF application
    public function indexAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	

    	//Set a file name (hardcoded = to change later)
		$fileName = '..\docs\debuter-avec-zend-framework.pdf';

    	$pdf = new Zend_Pdf();
    	$pdf = Zend_Pdf::load($fileName,null,true);
  	
    	// Add a new page with Zend_Pdf to the document
    	$pdf->pages[] = ($page1 = $pdf->newPage('A4'));

		//--------------ADD SIGNATURE-------------------------
    	if ($this->getRequest()->isPost()) {
    		//Get the data
    		$file = trim($this->getRequest()->getParam('data'));
    		 
    		//Decode data
    		$strEncodedData = str_replace(' ', '+', $file);
    		$strFilteredData = explode(',', $strEncodedData);
    		$strUnencoded = base64_decode($strFilteredData[1]);
    		
    		//put the content into a file
    		$filename = '../public/img/test.png';
    		file_put_contents($filename, $strUnencoded);

    	}
    	
		//Load signature image and add it to the PDF
    	$image = Zend_Pdf_Image::imageWithPath('../public/img/test.png');
    	$page1->drawImage($image, 100, 100, 400, 350);
    	
    	//------------SET AND SEND HEADERS TO ACTION------------------------------
    	$this->getResponse()->setHeader('Content-type', 'application/pdf', true);
    	//$this->getResponse()->setHeader('Content-disposition','inline;filename='.$module.'_'.$m_no.'.pdf', true);
    	
    	$this->getResponse()->setHeader('Cache-Control: no-cache, must-revalidate');
    	$this->getResponse()->setHeader('Content-Transfer-Encoding', 'binary', true);
    	$this->getResponse()->setHeader('Last-Modified', date('r'));
    	 
    	$this->getResponse()->clearBody();
    	$this->getResponse()->sendHeaders();

    	//------------SET BODY OF VIEW AS PDF------------------------------
    	$this->getResponse()->setBody($pdf->render());
    	
    }

    //Action that displays the PDF app with other functionnalities such as zoom, print, comment, refuse, validate...
    public function showfileAction()
    {
    	//Disabling the layout
    	$this->_helper->layout->disableLayout();   	
    }

    //Action that helps to sign a PDF
    public function signpdfAction()
    {
    }

}



