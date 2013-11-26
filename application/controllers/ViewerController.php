<?php

/*
 * File : ViewerController.php
* Author : Mathilde de l'Hermuzi�re
* Created : 19/11/2013
* Modified : 24/11/2013
* 1.1 : Mathilde de l'Hermuzière - cr�ation
* 1.2 : Mathilde de l'Hermuzière - insertion showPdfcontroller
*
* Controller that controls views for doing action on a PDF document
*
* Projet parapheur 2014
*/

class ViewerController extends Zend_Controller_Action
{
	//Function that initializes the controller
	public function init()
    {
    	//We get the ID of the document we have to display from the indexController
    	$request = $this->getRequest();
    	
    	//$sessioniddoc = new Zend_Session_Namespace('sessioniddoc');
    	//$id_doc = $sessioniddoc->id;
    	
    	//URL used to retrieve the document for the flipbook
    	//Url relative (here absolute is prefered, depends on the parameters in the URL)
    	//$url='../../../pdf/testSign.pdf';
    	//Url absolute
    	$url='../../../pdf/testSign.pdf';
    	//File PDF that is used = useful for signature and images
    	$this->filePath= APPLICATION_PATH.'\..\public\pdf\debuter-avec-zend-framework.pdf';

    	
    	$this->view->pdfurl=$url;
    	//$this->view->id_doc=$id_doc;
    	
    	//As we do not have Active Directory, we assume that our user ID is 6
    	$this->user_ID=6;
    	

    	//We get the ID of the document we have to display from the indexController
    	$request = $this->getRequest();
    	$this->id_document = $request->getParam('COURRIER_ID');
    	
    	$this->pdf = new Zend_Pdf();
    	$this->pdf = Zend_Pdf::load($this->filePath,null,true);
    	 
    }

    public function indexAction()
    {
        $this->_helper->layout->disableLayout();
    	//$this->_helper->viewRenderer->setNoRender(true);
    	
        $this->view->id_document= $this->id_document;
        
    	//Get the database infos
    	$db = Zend_Db_Table::getDefaultAdapter();
    	
    	//Call the display info
    	$this->showMeta($this->id_document,$db);
    	
    	//Call the form for add person
    	$this->addPersonPopup($this->id_document,$this->user_ID,$db);
    	
    	//Call the form for comments
    	$this->addCommentPopup($this->id_document,$this->user_ID,$db);
    	    	
    	//Add the validate form
    	$this->validatePopup();
    	
    	//Add the refuse form
    	$this->refusePopup();
    }
    
    //------------------------------FUNCTIONS FOR SIGNING A PDF --------------------------------------------
    
    //Action for signing the file
    public function signpdfAction()
    {
    }
    //Action for adding the signature to the file by a canvas
    public function signwithcanvasAction()
    {
        	
    	// Add a new page with Zend_Pdf to the document
    	//$this->pdf->pages[] = ($page1 = $this->pdf->newPage('A4'));
    	 
    	// Reverse page order
    	//$pdf->pages = array_reverse($pdf->pages);
    	//Delete the last page with signature (for test purpose) --> to be removed later.
    	//unset($pdf->pages[1]);
    	 
    	//--------------ADD SIGNATURE-------------------------
    	if ($this->getRequest()->isPost()) {
    		//Get the data
    		$file = trim($this->getRequest()->getParam('data'));
    		 
    		//Decode data
    		$strEncodedData = str_replace(' ', '+', $file);
    		$strFilteredData = explode(',', $strEncodedData);
    		$strUnencoded = base64_decode($strFilteredData[1]);
    		 
    		//put the content into a file
    		$fileimg = '../public/img/test2.png';
    		file_put_contents($fileimg, $strUnencoded);
    		 
    	}
    
    	//Load signature image and add it to the PDF
    	$image = Zend_Pdf_Image::imageWithPath('../public/img/test2.png');
    	
    	//Count pdf pages
    	$count = count($this->pdf->pages)-1;
    	
    	//Get the last page of pdf file
    	$lastpage = $this->pdf->pages[$count];
    	
    	//Get width and height of page
    	$width  = $lastpage->getWidth();
    	$height = $lastpage->getHeight();
    	
    	$imgWidth = $image->getPixelWidth();
    	$imgWidth = $imgWidth*0.50;
    	$imgHeight = $image->getPixelHeight();
    	$imgHeight = $imgHeight*0.50;
    	
    	//Draw image on the last page
    	$lastpage->drawImage($image, $width*0.45, $height*0.05, $width*0.45 + $imgWidth, $height*0.05 + $imgHeight);
    	//$page1->drawImage($image, 100, 100, 400, 350);
    
    	 
    	// Update the PDF document
    	//$pdf->save($this->fileName, true);
    	// Save document as a new file
    	$this->pdf->save('pdf/testSign.pdf'); 
    }
    
    //------------------------------FUNCTIONS FOR ADDING A COMMENT INTO THE PDF -----------------------------------------
    
    //Function that add a comment form to the view
    public function addCommentPopup($id_document, $user_ID, $db)
    {
    	$form = new Application_Form_Comment();
    	$this->view->commentForm = $form;
    	 
    	// Display old comment -------------------------------------------------
    	//Get the comment linked to the document
    	$sqlcom = 'SELECT * FROM COMMENTAIRE WHERE ID_COURRIER ='.$id_document;
    	//Get the result
    	$stmtcom = $db->query($sqlcom);
    	 
    	$contenu= array();
    	$date=array();
    	$id_author=array();
    	$id_comment=array();
    	 
    	while ($rowcom=$stmtcom->fetch()){//For each documents
    		$sqlaut='SELECT ID_ENTITEDESTINATAIRE FROM LIENINTERNE WHERE ID_LIENINTERNE ='.$rowcom['ID_COURRIERENTITE'];//Get the author
    		$stmtaut = $db->query($sqlaut);
    		$rowaut = $stmtaut->fetch();
    
    		$id_comment[]=$rowcom['ID_COMMENTAIRE'];
    		$id_author[]=$rowaut['ID_ENTITEDESTINATAIRE'];//Save the ID Value
    		$contenu[]=$rowcom['CONTENU'];//Save the title Value
    		$date[]=$rowcom['DATECREATION'];//->toString();//Save the date value
    	}
    	//Pass values to view
    	$this->view->id_comment=$id_comment;
    	$this->view->id_author=$id_author;
    	$this->view->contenu =$contenu;
    	$this->view->date =$date;
    }
    //Function that treats the request from the comment form
    public function addcommentpdfAction()
    {
    	$request = $this->getRequest();
    	 
    	//Get the database infos
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$form = $this->_getCommentForm();
    	$id_document = $this->id_document;
        
    	//If request is post
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    
    			//Get values from form
    			$formData = $form->getValues();
    
    			$type_commentaire = $form->getValue('type_commentaire');
    			$text_commentaire = $form->getValue('text_commentaire');
    			 
    			//Find the ID of the lieninterne between our user and the document
    			$sqlfind = 'SELECT ID_LIENINTERNE FROM LIENINTERNE WHERE ID_ENTITEDESTINATAIRE='.$this->user_ID.' AND ID_COURRIER ='.$this->id_document;
    			$stmtfind = $db->query($sqlfind);
    			$rowsfind = $stmtfind->fetchAll();
    			$id_lieninterne= $rowsfind[0]['ID_LIENINTERNE'];
    			 
    			//Date : for test
    			$date = '06/11/2011';
    			$commentaire = new Application_Model_DbTable_Commentaire();
    			$commentaire->ajouterCommentaire($id_document, $id_lieninterne, $text_commentaire, $date, $type_commentaire);
    		}
    	}
    }
    //Function that retrieves the form for comments
    private function _getCommentForm()
    {
    
    	if (!Zend_Registry::isRegistered('commentForm')){
    		require_once (APPLICATION_PATH .'/forms/Comment.php');
    		$form = new Application_Form_Comment();
    		Zend_Registry::set('commentForm', $form);
    	}else{
    		$form = Zend_Registry::get('commentForm');
    	}
    	return $form;
    	
    }
    
    //------------------------------FUNCTIONS FOR SHOWING META INFORMATIONS --------------------------------------------
    
    //Function that add a meta information form to the view
    public function showMeta($id_courrier, $db)
    {
    
    	//We get the infos from the database
    	 
    	//Meta infos from the table FICHIER ******************
    	$sqlfichier='SELECT NOMORIGINE FROM FICHIER WHERE ID_COURRIER = '.$id_courrier;
    	$stmtfichier = $db->query($sqlfichier);
    	$rowfichier = $stmtfichier->fetchAll();
    	 
    	$titre=$rowfichier[0]['NOMORIGINE'];//Save the title Value
    	 
    	//Pass values to view
    	$this->view->titre =$titre;
    	 
    	//Meta infos from the table LIENINTERNE **************
    	$sqlieninterne='SELECT * FROM LIENINTERNE WHERE ID_COURRIER ='.$id_courrier;
    	$stmtlieninterne = $db->query($sqlieninterne);
    	$id_demandeur = null;
    	$destinataires= array();
    	 
    	while ($rowlieninterne=$stmtlieninterne->fetch()){//For each documents
    		$entitedestinataire= $rowlieninterne['ID_ENTITEDESTINATAIRE'];
    		$id_demandeur= $rowlieninterne['ID_ENTITEEXPEDITEUR'];
    		$etat= $rowlieninterne['ID_ETATDESTINATAIRE'];
    
    		if($etat=="1"||$etat=="2"){//If the person is still in the workflow
    			$destinataires[]= $entitedestinataire;
    		}
    	}
    	//Pass values to view
    	$this->view->demandeur =$id_demandeur;
    	$this->view->destinataires=$destinataires;
    	 
    	//Meta infos from the table COURRIER *****************
    	$sqlcourrier='SELECT * FROM COURRIER WHERE ID_COURRIER = '.$id_courrier;//Get the creation date
    	$stmtcourrier = $db->query($sqlcourrier);
    	$rowcourrier = $stmtcourrier ->fetchAll();
    	 
    	$datecreation=$rowcourrier[0]['DATECREATION'];//Save the date value
    	$dateexpedition=$rowcourrier[0]['DATEEXPEDITION'];
    	$datelimitereponse=$rowcourrier[0]['DATELIMITEREPONSE'];
    	$priorite=$rowcourrier[0]['ID_PRIORITE'];
    	if($priorite!=null){
    		//Now that we have the id of classification, we want the libelle
    		$sqlpriorite='SELECT LIBELLE FROM T_PRIORITE WHERE ID_PRIORITE='.$priorite;
    		$stmtpriorite = $db->query($sqlpriorite);
    		$rowpriorite = $stmtpriorite ->fetchAll();
    		$prioritelibelle=$rowpriorite[0]['LIBELLE'];
    	}
    	else{
    		$prioritelibelle=null;
    	}
    	 
    	$classification=$rowcourrier[0]['ID_CLASSIFICATION'];
    	if($classification!=null){
    		//Now that we have the id of classification, we want the libelle
    		$sqlclassification='SELECT LIBELLE FROM T_CLASSIFICATION WHERE ID_CLASSIFICATION='.$classification;
    		$stmtclassification = $db->query($sqlclassification);
    		$rowclassification = $stmtclassification ->fetchAll();
    		$classificationlibelle=$rowclassification[0]['LIBELLE'];
    	}
    	else{
    		$classificationlibelle=null;
    	}
    	 
    	$type=$rowcourrier[0]['ID_TYPECOURRIER'];
    	if($type!=null){
    		//Now that we have the id of type, we want the libelle
    		$sqltype='SELECT LIBELLE FROM T_TYPECOURRIER WHERE ID_TYPECOURRIER='.$type;
    		$stmttype = $db->query($sqltype);
    		$rowtype = $stmttype ->fetchAll();
    		$typelibelle=$rowtype[0]['LIBELLE'];
    	}
    	else{
    		$typelibelle=null;
    	}
    	 
    	$codeexterne=$rowcourrier[0]['CODEEXTERNE'];
    	$groupecreateur=$rowcourrier[0]['ID_GROUPECREATEUR'];
    	$dernieremodif=$rowcourrier[0]['IRDERNIEREMODIF'];
    	 
    	//Pass values to view
    	$this->view->datecreation =$datecreation;
    	$this->view->dateexpedition =$dateexpedition;
    	$this->view->datelimitereponse =$datelimitereponse;
    	$this->view->priorite =$prioritelibelle;
    	$this->view->classification =$classificationlibelle;
    	$this->view->type =$typelibelle;
    	$this->view->codeexterne =$codeexterne;
    	$this->view->groupecreateur =$groupecreateur;
    	$this->view->dernieremodif =$dernieremodif;
    	 
    }

    //------------------------------FUNCTIONS FOR REFUSING A FILE --------------------------------------------
    
    //Function that add a comment form to the view
    public function refusePopup()
    {
    	 
    	$form = new Application_Form_Refuse();
    	 
    	$this->view->refuseForm = $form;
    	 
    }
    //Function that retrieves the form for refusing a document
    private function _getRefuseForm()
    {
    
    	if (!Zend_Registry::isRegistered('refuseForm')){
    		require_once (APPLICATION_PATH . '/forms/Refuse.php');
    		$form = new Application_Form_Refuse();
    		Zend_Registry::set('refuseForm', $form);
    	}else{
    		$form = Zend_Registry::get('refuseForm');
    	}
    	return $form;
    }
    //Function that treats the request from the refuse form
    public function refusepdfAction()
    {
    
    	$request = $this->getRequest();
    	$form = $this->_getRefuseForm();
    		
    	//Get the database infos
    	$db = Zend_Db_Table::getDefaultAdapter();
    
    	$id_document = $request->getParam('COURRIER_ID');
    
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    
    			//Add validation image to PDF
    				
    			//Get image
    			$image = Zend_Pdf_Image::imageWithPath('../public/img/icons/ico_refused.png');
    				
    			//Count pdf pages
    			$count = count($this->pdf->pages)-1;
    				
    			//Get the last page of pdf file
    			$lastpage = $this->pdf->pages[$count];
    				
    			//Get width and height of page
    			$width  = $lastpage->getWidth();
    			$height = $lastpage->getHeight();
    				
    			$imgWidth = $image->getPixelWidth();
    			$imgHeight = $image->getPixelHeight();
    				
    			//Draw image on the last page
    			$lastpage->drawImage($image, $width*0.45, $height*0.05, $width*0.45 + $imgWidth, $height*0.05 + $imgHeight);
    				
    			//------------------------------------Do associate changes into database---------------------------
    			$text_commentaire = $form->getValue('text_commentaire_refuse');
    				
    			//Find the ID of the lieninterne between our user and the document
    			$sqlfind = 'SELECT ID_LIENINTERNE FROM LIENINTERNE WHERE ID_ENTITEDESTINATAIRE='.$this->user_ID.' AND ID_COURRIER ='.$id_document;
    			$stmtfind = $db->query($sqlfind);
    			$rowsfind = $stmtfind->fetchAll();
    			$id_lieninterne= $rowsfind[0]['ID_LIENINTERNE'];
    			$expediteur= $rowsfind[0]['ID_ENTITEEXPEDITEUR'];
    				
    			//Date : for test
    			if($text_commentaire!=null){
    				$date = '06/11/2011';
    				$commentaire = new Application_Model_DbTable_Commentaire();
    				$commentaire->ajouterCommentaire($id_document, $id_lieninterne, $text_commentaire, $date, '1');
    			}
    
    			//------------------------------------------------------------------------------------------------
    			//Archive the link between the user and the document
    			$sqlupdate='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=5 WHERE ID_LIENINTERNE='.$id_lieninterne;
    			$stmtupdate = $db->query($sqlupdate);
    
    			//Update the state of the expeditor so he can see the file in his index of documents
    			$sqlupdatereceiver='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=1 WHERE ID_ENTITEDESTINATAIRE='.$expediteur;
    			$stmtupdatereceiver = $db->query($sqlupdatereceiver);
    
    			//------------------------------------------------------------------------------------------------
    			//Save the PDF
    			// Save document as a new file
    			$this->pdf->save('pdf/testSign.pdf');
    			//Redirect the action
    			$this->_helper->redirector('index','index');
    		}
    	}
    }
    
    //------------------------------FUNCTIONS FOR VALIDATING A FILE --------------------------------------------
    
    //Function that add a validation form to the view
    public function validatePopup()
    {
    	 
    	$form = new Application_Form_Validate();
    	 
    	$this->view->validateForm = $form;
    	 
    }
    //Function that treats the request from the validation form
    public function validatepdfAction()
    {
    
    	//Get the database infos
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$request = $this->getRequest();
    	$form = $this->_getValidateForm();
    	 
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    
    			//Add validation image to PDF
    			 
    			//Get image
    			$image = Zend_Pdf_Image::imageWithPath('../public/img/icons/ico_validated.png');
    			 
    			//Count pdf pages
    			$count = count($this->pdf->pages)-1;
    			 
    			//Get the last page of pdf file
    			$lastpage = $this->pdf->pages[$count];
    			 
    			//Get width and height of page
    			$width  = $lastpage->getWidth();
    			$height = $lastpage->getHeight();
    			 
    			$imgWidth = $image->getPixelWidth();
    			$imgHeight = $image->getPixelHeight();
    			 
    			//Draw image on the last page
    			$lastpage->drawImage($image, $width*0.45, $height*0.05, $width*0.45 + $imgWidth, $height*0.05 + $imgHeight);
    			 
    			//Do associate changes into database
    			//------------------------------------------------------------------------------------------------
    			//Archive the link between the user and the document
    			$sqlupdate='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=3 WHERE ID_LIENINTERNE='.$id_lieninterne;
    			$stmtupdate = $db->query($sqlupdate);
    			 
    			//Find the next receiver of the document
    			$sqlnewreceiver='SELECT * FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE=5 AND ID_DOCUMENT='.$id_document.' ORDER BY DATECREATION';
    			$stmtreceiver = $db->query($sqlreceiver);
    			$rowsreceiver = $stmtreceiver->fetchAll();
    			$id_lieninternereceiver= $rowsreceiver[0]['ID_LIENINTERNE'];
    			 
    			if($rowsreceiver!=0){// There is still someone in the workflow
    				//Update the state of this receiver so he can see the file in his index of documents
    				$sqlupdatereceiver='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=1 WHERE ID_LIENINTERNE='.$id_lieninternereceiver;
    				$stmtupdatereceiver = $db->query($sqlupdatereceiver);
    			}
    			else{//Everyone approved the document, we can send it to the first demandeur
    				$sqlfinddemandeur='SELECT * FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE=6 AND ID_DOCUMENT='.$id_document;
    				$stmtfinddemandeur = $db->query($sqlfinddemandeur);
    				$rowsfinddemandeur = $stmtfinddemandeur->fetchAll();
    				$id_lieninternedemandeur= $rowsfinddemandeur[0]['ID_LIENINTERNE'];
    
    				$sqlupdatedemandeur='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=1 WHERE ID_LIENINTERNE='.$id_lieninternedemandeur;
    				$stmtupdatedemandeur = $db->query($sqlupdatereceiver);
    			}
    			 
    			//------------------------------------------------------------------------------------------------    			//Save the PDF
    			// Save document as a new file
    			$this->pdf->save('pdf/testSign.pdf');
    			 
    			//Redirect the action
    			$this->_helper->redirector('index','index');
    		}
    	}
    	 
    }
    //Function that retrieves the form for validation
    private function _getValidateForm()
    {
    	 
    	if (!Zend_Registry::isRegistered('validateForm')){
    		require_once (APPLICATION_PATH . '/forms/Validate.php');
    		$form = new Application_Form_Validate();
    		Zend_Registry::set('validateForm', $form);
    	}else{
    		$form = Zend_Registry::get('validateForm');
    	}
    	return $form;
    }
    
    //------------------------------FUNCTIONS ADDING A PERSON --------------------------------------------
    
    //Function that add a add-a-person form to the view
    public function addPersonPopup($id_document, $user_ID, $db)
    {
    	$form = new Application_Form_Addperson();
    
    	$this->view->addpersonForm = $form;
    	$this->view->id_doc= $id_document;
    	 
    	// Show the workflow of persons waiting ---------------------------------
    	//Get the comment linked to the document
    	$sqldest = 'SELECT * FROM LIENINTERNE WHERE (ID_ETATDESTINATAIRE=1 OR ID_ETATDESTINATAIRE=2) AND ID_COURRIER ='.$id_document.'ORDER BY DATECREATION';
    	//Get the result
    	$stmtdest = $db->query($sqldest);
    
    	$expediteur= array();
    	$destinataire=array();
    	$etat=array();
    	$date=array();
    
    	while ($rowdest=$stmtdest->fetch()){//For each documents
    		$idlien[]=$rowdest['ID_LIENINTERNE'];
    		$expediteur[]=$rowdest['ID_ENTITEEXPEDITEUR'];
    		$destinataire[]=$rowdest['ID_ENTITEDESTINATAIRE'];//Save the ID Value
    		$etat[]=$rowdest['ID_ETATDESTINATAIRE'];//Save the title Value
    		$date[]=$rowdest['DATECREATION'];//->toString();//Save the date value
    	}
    	//Pass values to view
    	$this->view->idlien = $idlien;
    	$this->view->expediteur = $expediteur;
    	$this->view->destinataire = $destinataire;
    	$this->view->etat = $etat;
    	$this->view->date = $date;
    }
    //Function that treats the request from the form
    public function addpersonpdfAction(){
    	 
    	$request = $this->getRequest();
    
    	//Get the database infos
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$form = $this->_getAddPersonForm();
    	 
    	$id_document = $request->getParam('COURRIER_ID');
    	 
    	//If request is post
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    			 
    			$formData = $form->getValues();
    
    			$id_dest = $form->getValue('id_person');
    			$IRauteur = $form->getValue('IRauteur');
    			$type = $form->getValue('type');
    			$date = '06/11/2011';
    
    			$lieninterne = new Application_Model_DbTable_Lieninterne();
    			$lieninterne->ajouterLieninterne($id_document, $this->user_ID, $id_dest, $type, '1', 'N', $date, $IRauteur);
    
    			//return $this->_helper->redirector('showfile');
    
    		}
    	}
    }
    //Function that retrieves the form for adding a person
    private function _getAddPersonForm()
    {
    
    	if (!Zend_Registry::isRegistered('addpersonForm')){
    		require_once (APPLICATION_PATH . '/forms/Addperson.php');
    		$form = new Application_Form_Addperson();
    		Zend_Registry::set('addpersonForm', $form);
    	}else{
    		$form = Zend_Registry::get('addpersonForm');
    	}
    	return $form;
    }
}

