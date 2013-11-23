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
* 1.10 : Mathilde de l'Hermuzi�re - link with the index of documents and comment
* 1.11 : Hina Tufail - méta informations and comment pop ups
* 1.12 : Mathilde de l'Hermuzi�re - meta informations revision
* 1.13 : Hina Tufail - modification - 20/11/2013
* 1.14 : Hina Tufail - modification - 23/11/2013
*
* Controller that controls views for doing action on a PDF document
*
* Projet parapheur 2014
*/

class ShowPdfController extends Zend_Controller_Action
{

    public function init()
    {
    	//We get the ID of the document we have to display from the indexController
    	$request = $this->getRequest();
    	$this->id_document = $request->getParam('COURRIER_ID');
    	
    	//Get the PDF
    	//Set a file name (hardcoded = to change later)
    	$this->fileName = '..\docs\debuter-avec-zend-framework.pdf';
    	
    	$pdf = new Zend_Pdf();
    	$pdf = Zend_Pdf::load($this->fileName,null,true);
    	
    	$this->pdf = $pdf;

    	//As we do not have Active Directory, we assume that our user ID is 6
    	$this->user_ID=6;

    }

    public function indexAction()
    {
     	
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
  	
    	$this->signWithCanvas();
    	
    	//------------SET AND SEND HEADERS TO ACTION------------------------------
    	$this->getResponse()->setHeader('Content-type', 'application/pdf', true);
    	//$this->getResponse()->setHeader('Content-disposition','inline;filename='.$module.'_'.$m_no.'.pdf', true);
    	
    	$this->getResponse()->setHeader('Cache-Control: no-cache, must-revalidate');
    	$this->getResponse()->setHeader('Content-Transfer-Encoding', 'binary', true);
    	$this->getResponse()->setHeader('Last-Modified', date('r'));
    	 
    	$this->getResponse()->clearBody();
    	$this->getResponse()->sendHeaders();

    	//------------SET BODY OF VIEW AS PDF------------------------------
    	$this->getResponse()->setBody($this->pdf->render());
    	
    }
//------------------------------FUNCTIONS FOR SIGNING A PDF --------------------------------------------
    public function signpdfAction()
    {
    }

    public function signWithCanvas()
    {
    	 
    	// Add a new page with Zend_Pdf to the document
    	$this->pdf->pages[] = ($page1 = $this->pdf->newPage('A4'));
    	
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
    	$page1->drawImage($image, 100, 100, 400, 350);

    	
    	// Update the PDF document
    	//$pdf->save($this->fileName, true);
    	// Save document as a new file
    	$this->pdf->save('pdf/test.pdf');

    	
    }
    
//------------------------------FUNCTIONS FOR SHOWING A PDF WITH ALL TIS FUNCTIONNALITIES ----------------------------
    public function showfileAction()
    {
    	//Disabling the layout
    	$this->_helper->layout->disableLayout();
    	
    	//Getting the database connexion
     	$db = Zend_Db_Table::getDefaultAdapter();
    	 
     	//We get the ID of the document we have to display from the indexController
     	$request = $this->getRequest();
     	$id_document = $request->getParam('COURRIER_ID');
     	
		$this->view->id_document= $this->id_document;
		/*
     	//We have to check is this user is habilitated to see this document...
     	//$sql1 = 'SELECT ID_ETATDESTINATAIRE FROM LIENINTERNE WHERE ID_COURRIER = 82 AND ID_ENTITEDESTINATAIRE = 6';
     	$sql1 = 'SELECT ID_ETATDESTINATAIRE FROM LIENINTERNE WHERE ID_COURRIER = '.$id_document.'AND ID_ENTITEDESTINATAIRE = 6';
     	//Get the result
     	$stmt1 = $db->query($sql1);
  		$rows1 = $stmt1->fetchAll();*/
//  		$etat=$rows1[0]['ID_ETATDESTINATAIRE'];
 		
//  		if($etat==1||$etat==2){//user is allowed to see the document
// 	    	//We get the ID of the file from the ID of the document in the database
// 	    	$sql='SELECT ID_FICHIER FROM FICHIER WHERE ID_COURRIER ='.$id_document;
// 	    	//Get the result
// 	    	$stmt = $db->query($sql);
// 	    	$rows=$stmt->fetchAll();
// 	    	$id_fichier=$rows[0]['ID_FICHIER'];
	    	
// 	    	//Select the file from ID and declob it
// 	    	$conn = oci_connect('DBA_PARAPHEUR', '12345678', 'XE');
// 	    	if (!$conn){
// 	    		echo 'Connection error.';
// 	    	}
// 	    	$sql = 'SELECT CONTENU FROM CONTENU WHERE ID_FICHIER=:id_fichier';
// 	    	$stid = oci_parse($conn, $sql);
// 	    	oci_bind_by_name($stid, ":id_fichier", $id_fichier);
	    	//$result = oci_execute($stid);
	    	
	    	//Fetch the row
	    	//if($result !== false){
	    		//while($row = oci_fetch_assoc($stid)){
	    			//echo $row['CONTENU']->load();
	    			//or
	    			//echo $row['CONTENU']->read(2000);
	    		//}
	    	//}
	    	//---------------------SET AND SEND HEADER AS TO INDICATE IT IS A PDF APPLICATION---------------
	    	//$this->getResponse()->setHeader('Content-type', 'application/pdf', true);
	    	//$this->getResponse()->setHeader('Content-disposition','inline;filename='.$module.'_'.$m_no.'.pdf', true);
	    	
	    	//$this->getResponse()->setHeader('Cache-Control: no-cache, must-revalidate');
	    	//$this->getResponse()->setHeader('Content-Transfer-Encoding', 'binary', true);
	    	//$this->getResponse()->setHeader('Last-Modified', date('r'));
	    	
	    	//$this->getResponse()->clearBody();
	    	//$this->getResponse()->sendHeaders();
	    	
	    	//Set the body
	    	//$this->getResponse()->setBody($pdf->render());
 		//}
// 	    else{//User is not allowed to see the document
// 	    	//Redirection to the index
// 	    	echo $this->url(array('controller' => 'index',
// 	    					      'action'=>'index'),
// 	    			              'default',true);
// 	    }	
    
  		//Get the database infos
  		$db = Zend_Db_Table::getDefaultAdapter();
		
		//Call the form for add person
		$this->addPersonPopup($id_document,$this->user_ID,$db);
		
  		//Call the form for comments
  		$this->addCommentPopup($id_document,$this->user_ID,$db);

  		//Call the display info
  		$this->showMeta($id_document,$db);
  		
  		$this->validatePopup();
    
    }

//------------------------------FUNCTIONS FOR ADDING A COMMENT INTO THE PDF -----------------------------------------
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
    

    public function addcommentpdfAction()
    {
    	$request = $this->getRequest();
    	
    	//Get the database infos
  		$db = Zend_Db_Table::getDefaultAdapter();
  		$form = $this->_getCommentForm();
  		
     	$id_document = $request->getParam('COURRIER_ID');
  		
    	//If request is post
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    				
    			//Get values from form
    			$formData = $form->getValues();
    				
    			$type_commentaire = $form->getValue('type_commentaire');
    			$text_commentaire = $form->getValue('text_commentaire');
    			
    			//Find the ID of the lieninterne between our user and the document
    			$sqlfind = 'SELECT ID_LIENINTERNE FROM LIENINTERNE WHERE ID_ENTITEDESTINATAIRE='.$this->user_ID.' AND ID_COURRIER ='.$id_document;
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

    private function _getCommentForm()
    {
    	 
    	if (!Zend_Registry::isRegistered('commentForm')){
    		require_once (APPLICATION_PATH . '/forms/Comment.php');
    		$form = new Application_Form_Comment();
    		Zend_Registry::set('commentForm', $form);
    	}else{
    		$form = Zend_Registry::get('commentForm');
    	}
    	return $form;
    }

//------------------------------FUNCTIONS FOR SHOWING META INFORMATIONS --------------------------------------------

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

//------------------------------FUNCTIONS FOR VALIDATING A FILE --------------------------------------------
    public function validatePopup()
    {
    	
    	$form = new Application_Form_Validate();
    	
    	$this->view->validateForm = $form;
    	
    }

    public function validatepdfAction()
    {

    	$request = $this->getRequest();
    	$form = $this->_getValidateForm();
    	
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    			 
    			//Add validation image to PDF
    			$image = Zend_Pdf_Image::imageWithPath('../public/img/icons/ico_validated.png');
    			
    			$this->pdf->pages[0]->drawImage($image, 50, 50, 40, 35);
    			
    			//Do associate changes into database
    			 
    			//Save the PDF
    			// Save document as a new file
    			$this->pdf->save('pdf/test.pdf');
    			//Redirect the action
    			//$this->_helper->redirector('showfile');
    		}
    	}
    	
    }

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





