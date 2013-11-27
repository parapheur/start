<?php

/*
* File : AddafileController.php
* Author : Mathilde De l'Hermuzière
* Created : 09/11/2013
* Modified : 16/11/2013
* 1.1 : Mathilde de l'Hermuzière - Creation and modification for adding clob
* 1.2 : Hina Tufail - modification
* 1.3: Mathilde de l'Hermuziere - redirection into a filer
* 
* Controller that controls views for adding a file into database
*
* Projet parapheur 2014
*/

class AddafileController extends Zend_Controller_Action
{

    public function init()
    {
    }

    //Test action for retrieving document into clob format from database
    public function indexAction()
    {
    	//Database connexion
    	$id = '63';
    	$conn = oci_connect('DBA_PARAPHEUR', '12345678', 'XE');
    	if (!$conn){
    		echo 'Connection error.';
    	}
    	//Select the file from ID
    	$sql = 'SELECT * FROM CONTENU WHERE ID_FICHIER=:id';
    	$stid = oci_parse($conn, $sql);
    	oci_bind_by_name($stid, ":id", $id);
    	$result = oci_execute($stid);
    	
    	//Fetch the row
    	if($result !== false){
	    	while($row = oci_fetch_assoc($stid)){
	            echo $row['CONTENU']->load();
	            //or
	            echo $row['CONTENU']->read(2000);
	        }
    	}
     	
     	//---------------------SET AND SEND HEADER AS TO INDICATE IT IS A PDF APPLICATION---------------
     	$this->getResponse()->setHeader('Content-type', 'application/pdf', true);
     	$this->getResponse()->setHeader('Content-disposition','inline;filename='.$module.'_'.$m_no.'.pdf', true);
     	
     	$this->getResponse()->setHeader('Cache-Control: no-cache, must-revalidate');
     	$this->getResponse()->setHeader('Content-Transfer-Encoding', 'binary', true);
     	$this->getResponse()->setHeader('Last-Modified', date('r'));
     	
     	$this->getResponse()->clearBody();
     	$this->getResponse()->sendHeaders();
     	
     	//Set the body
     	$this->getResponse()->setBody($pdf->render());
    }

    //Action that adds a document into clob format in the database
	public function signAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Addafile();
 
        if ($this->getRequest()->isPost()) {
             if ($form->isValid($request->getPost())) {
              	
             	//Set destination for the PDF file : data file into application
             	$upload = new Zend_File_Transfer_Adapter_Http();
              	$upload->setDestination(realpath(APPLICATION_PATH . '/../public/pdf/'));
             	
             	if (!$upload->receive()) {
             		$messages = $upload->getMessages();
             		echo implode("\n", $messages);
             	}
              	try { 
              		// call receive() before getValues()
              		$upload->receive();
              	} catch (Zend_File_Transfer_Exception $e) {
              		$e->getMessage();
              	}
                $formData = $form->getValues();
                $file = new Zend_Form_Element_File('file');
              	//$filename = $upload->getFileName('upfile');
              	//$filesize = $upload->getFileSize('upfile');
              	//$filemimeType = $upload->getMimeType('upfile');
              	
              	//echo $filename;
              	//echo $filesize;
              	//echo $filemimeType;
              	 
//              	$dstFilePath = '/images/'.$filename;
             	
//              	$filterFileRename = new Zend_Filter_File_Rename(array('target' => $dstFilePath, 'overwrite' => true));
//              	$filterFileRename->filter($filename); //move uploade file to destination
             		
//              	$file = file_get_contents($fileName);
					//$url=APPLICATION_PATH.'\data\minicdcparapheur.pdf';
             		//$pdf=Zend_Pdf::parse(file_get_contents($filename));
//              		$path='/data'.$name;
//              		$file=file_get_contents('/data/minicdcparapheur.pdf');
//              		$pdf=Zend_Pdf::parse(file);
             		//$pdf = Zend_Pdf::load('C:\a.pdf');
             		//$pdf = Zend_Pdf::load(realpath(APPLICATION_PATH . '/data/a.pdf'));
             		//$pdf = Zend_Pdf::load($filename);
					$title = $form->getValue('titre');
					
					$author = $form->getValue('id_author');
					$des1 = $form->getValue('id_dest1');
					$des2 = $form->getValue('id_dest2');
					$des3 = $form->getValue('id_dest3');
					
					$id_typecourrier=1;
					$id_typefichier=1; //is a PDF
					$taille=500;
					
					//Get from tables Courrier, Fichier and Contenu
					$courrier = new Application_Model_DbTable_Courrier();
					$fichier = new Application_Model_DbTable_Fichier();
					$contenu = new Application_Model_DbTable_Contenu();
					
					//$id_courrier=  $courrier->ajouterCourrier($id_typecourrier);
					//echo $id_courrier;
					
					//$id_fichier=$fichier->ajouterFichier($id_courrier, $id_typefichier, $taille, $title);
					//echo $id_fichier;
					
					//$pdfString = $pdf->render();
					//$contenu->ajouterContenu($id_fichier, $pdfString);
					$this->_helper->redirector('index', 'index');
             }
        }
 
        $this->view->form = $form;
    }
    
    //Test action for the flipbook - to be deleted later
    public function imagickAction()
    {    	$this->_helper->layout->disableLayout();
    }


}

