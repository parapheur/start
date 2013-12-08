<?php

/*
 * Fichier : ViewerController.php
* Auteur : Mathilde de l'Hermuzi�re
* Création : 19/11/2013
* Modifié : 24/11/2013
* 1.1 : Mathilde de l'Hermuzière - création
* 1.2 : Mathilde de l'Hermuzière - insertion showPdfcontroller
*
* Controller pour la vue permettant de travailler sur un document PDF
*
* Projet parapheur 2014
*/

class ViewerController extends Zend_Controller_Action
{
	//Fonction initialisant le controller
	public function init()
    {
    	//On récupère l'id du document que l'on doit afficher à partir de l'indexController
    	$request = $this->getRequest();
    	
    	//$sessioniddoc = new Zend_Session_Namespace('sessioniddoc');
    	//$id_doc = $sessioniddoc->id;
    	
    	//$this->view->id_doc=$id_doc;
    	
    	//Comme nous n'avons pas Active Directory, on suppose que l'ID utilisateur est de 6
    	$this->user_ID=6;
    	

    	//On récupère l'ID du document que nous souhaitons afficher à partir de l'indexController
    	$request = $this->getRequest();
    	$this->id_document = $request->getParam('COURRIER_ID');
    	
    	//URL utilisé pour récupérer le document pour la liseuse
    	$url='../../../pdf/'.$this->id_document.'.pdf';
    	//Fichier PDF qui est utilisé = useful for signature and images
    	$this->filePath= APPLICATION_PATH.'\..\public\pdf\ID-'.$this->id_document.'.pdf';
    	
    	$this->view->pdfurl=$url;
    	
    	$this->pdf = new Zend_Pdf();
    	$this->pdf = Zend_Pdf::load($this->filePath,null,true);
    	 
    }

    //Application PDF
    public function indexAction()
    {
        $this->_helper->layout->disableLayout();
    	
        $this->view->id_document= $this->id_document;
        
    	//Récupérer les informations de la base de données
    	$db = Zend_Db_Table::getDefaultAdapter();
    	
    	//Appel du pop up des méta-informations
    	$this->showMeta($this->id_document,$db);
    	
    	//Appel du formulaire pour l'ajout d'une personne
    	$this->addPersonPopup($this->id_document,$this->user_ID,$db);
    	
    	//Appel du formulaire pop up pour les commentaires
    	$this->addCommentPopup($this->id_document,$this->user_ID,$db);
    	    	
    	//Appel du formulaire de validation
    	$this->validatePopup();
    	
    	//Appel du formulaire de refus
    	$this->refusePopup();
    }
    
    //------------------------------FONCTIONS POUR SIGNER UN PDF --------------------------------------------
    
    //Action pour signer le fichier
    public function signpdfAction()
    {
    }
    //Action pour l'ajout de la signature au document via un canvas
    public function signwithcanvasAction()
    {
        	
    	// Ajout d'une nouvelle page avec Zend_Pdf au document
    	//$this->pdf->pages[] = ($page1 = $this->pdf->newPage('A4'));
    	 
    	// Renverser l'ordre des pages
    	//$pdf->pages = array_reverse($pdf->pages);
    	 
    	//--------------AJOUT SIGNATURE-------------------------
    	if ($this->getRequest()->isPost()) {
    		//Récupérer les données
    		$file = trim($this->getRequest()->getParam('data'));
    		 
    		//Décoder les données
    		$strEncodedData = str_replace(' ', '+', $file);
    		$strFilteredData = explode(',', $strEncodedData);
    		$strUnencoded = base64_decode($strFilteredData[1]);
    		 
    		//placer le contenu dans un fichier
    		$fileimg = '../public/img/test2.png';
    		file_put_contents($fileimg, $strUnencoded);
    		 
    	}
    
    	//Charger l'image de la signature et l'ajouter au PDF
    	$image = Zend_Pdf_Image::imageWithPath('../public/img/test2.png');
    	
    	//Compter les pages pdf
    	$count = count($this->pdf->pages)-1;
    	
    	//Obtenir la dernière page du PDF
    	$lastpage = $this->pdf->pages[$count];
    	
    	//Obtenir la longueur et la largeur de la page
    	$width  = $lastpage->getWidth();
    	$height = $lastpage->getHeight();
    	
    	$imgWidth = $image->getPixelWidth();
    	$imgWidth = $imgWidth*0.50;
    	$imgHeight = $image->getPixelHeight();
    	$imgHeight = $imgHeight*0.50;
    	
    	//Dessiner une image sur la dernière page
    	$lastpage->drawImage($image, $width*0.45, $height*0.05, $width*0.45 + $imgWidth, $height*0.05 + $imgHeight);
    
    	 
    	// Mettre à jour le document PDF
    	//$pdf->save($this->fileName, true);

    	// Enregistrer le document en tant que nouveau fichier
    	$string_save = 'pdf-sign/'.$this->id_document.'.pdf';
    	$this->pdf->save($string_save); 
    	/* TO DELETE THE FILE IN PDF FOLDER - Ne pas effacer
    	$mask = "pdf/".$this->id_document.".pdf";
    	array_map( "unlink", glob( $mask ) );*/
    }
    
    //------------------------------FONCTIONS D'AJOUT DE COMMENTAIRES DANS UN PDF -----------------------------------------
    
    //Fonction permettant l'ajout d'un formulaire pour l'ajout de commentaires
    public function addCommentPopup($id_document, $user_ID, $db)
    {
    	$form = new Application_Form_Comment();
    	$this->view->commentForm = $form;
    	 
    	// Affichage des anciens commentaires via la base de données
    	// Obtenir le commentaire relié audocument
    	$sqlcom = 'SELECT * FROM COMMENTAIRE WHERE ID_COURRIER ='.$id_document;
    	//Récupérer le résultat de la requête
    	$stmtcom = $db->query($sqlcom);
    	 
    	$contenu= array();
    	$date=array();
    	$id_author=array();
    	$id_comment=array();
    	 
    	//Pour chaque document
    	while ($rowcom=$stmtcom->fetch()){
    		$sqlaut='SELECT ID_ENTITEDESTINATAIRE FROM LIENINTERNE WHERE ID_LIENINTERNE ='.$rowcom['ID_COURRIERENTITE'];//Get the author
    		$stmtaut = $db->query($sqlaut);
    		$rowaut = $stmtaut->fetch();
    
    		$id_comment[]=$rowcom['ID_COMMENTAIRE'];
    		$id_author[]=$rowaut['ID_ENTITEDESTINATAIRE'];//Enregistrer la valeur de l'ID
    		$contenu[]=$rowcom['CONTENU'];//Enregistrer la valeur du titre
    		$date[]=$rowcom['DATECREATION'];//->toString();//Enregistrer la valeur de la date
    	}
    	//Passer les valeurs à la vue
    	$this->view->id_comment=$id_comment;
    	$this->view->id_author=$id_author;
    	$this->view->contenu =$contenu;
    	$this->view->date =$date;
    }
    
    //Fonction traitant les requêtes à partir du formulaire de commentaires
    public function addCommentPdfAction()
    {
    	$request = $this->getRequest();
    	 
    	//Récupérer les informations de la base de données
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$form = $this->_getCommentForm();
    	$id_document = $this->id_document;
        
    	//Si la requête est postée
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    
    			//Récupérer les valeurs à partir du formulaire de commentaire
    			$formData = $form->getValues();
    
    			$type_commentaire = $form->getValue('type_commentaire');
    			$text_commentaire = $form->getValue('text_commentaire');
    			 
    			//Récupérer l'ID du lieninterne entre notre utilisateur et le document
    			$sqlfind = 'SELECT ID_LIENINTERNE FROM LIENINTERNE WHERE ID_ENTITEDESTINATAIRE='.$this->user_ID.' AND ID_COURRIER ='.$this->id_document;
    			$stmtfind = $db->query($sqlfind);
    			$rowsfind = $stmtfind->fetchAll();
    			$id_lieninterne= $rowsfind[0]['ID_LIENINTERNE'];
    			 
    			//Date
    			$date = new Zend_Date();
    			$month= $date->get(Zend_Date::MONTH);
    			$day = $date->get(Zend_Date::DAY);
    			$year=$date->get(Zend_Date::YEAR);
    			$date = $day.'/'.$month.'/'.$year;

    			$commentaire = new Application_Model_DbTable_Commentaire();
    			$commentaire->ajouterCommentaire($id_document, $id_lieninterne, $text_commentaire, $date, $type_commentaire);
    			$this->_helper->redirector('index','viewer','default',array('COURRIER_ID' => $this->id_document));
    		}
    	}
    }
    //Fonction récupérant le formulaire de commentaire
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
    
    //------------------------------FONCTIONS D'AFFICHER DES META INFORMATIONS --------------------------------------------
    
    //Fonction permettant l'ajout du formulaire de meta informations à la vue
    public function showMeta($id_courrier, $db)
    {
    
    	//On récupère les informations de la base de données
    	 
    	//Meta infos de la table FICHIER ******************
    	$sqlfichier='SELECT NOMORIGINE FROM FICHIER WHERE ID_COURRIER = '.$id_courrier;
    	$stmtfichier = $db->query($sqlfichier);
    	$rowfichier = $stmtfichier->fetchAll();
    	 
    	$titre=$rowfichier[0]['NOMORIGINE'];//Save the title Value
    	 
    	//Passage des paramètres à la vue
    	$this->view->titre =$titre;
    	 
    	//Meta infos de la table LIENINTERNE **************
    	$sqlieninterne='SELECT * FROM LIENINTERNE WHERE ID_COURRIER ='.$id_courrier;
    	$stmtlieninterne = $db->query($sqlieninterne);
    	$id_demandeur = null;
    	$destinataires= array();
    	 
    	//pour chaque document
    	while ($rowlieninterne=$stmtlieninterne->fetch()){
    		$entitedestinataire= $rowlieninterne['ID_ENTITEDESTINATAIRE'];
    		$id_demandeur= $rowlieninterne['ID_ENTITEEXPEDITEUR'];
    		$etat= $rowlieninterne['ID_ETATDESTINATAIRE'];
    
    		if($etat=="1"||$etat=="2"){//Si la personne est toujours dans le workflow
    			$destinataires[]= $entitedestinataire;
    		}
    	}
    	//Passage des paramètres à la vue
    	$this->view->demandeur =$id_demandeur;
    	$this->view->destinataires=$destinataires;
    	 
    	//Meta infos de la table COURRIER *****************
    	$sqlcourrier='SELECT * FROM COURRIER WHERE ID_COURRIER = '.$id_courrier;//récupérer la date de création
    	$stmtcourrier = $db->query($sqlcourrier);
    	$rowcourrier = $stmtcourrier ->fetchAll();
    	 
    	$datecreation=$rowcourrier[0]['DATECREATION'];//Enregistrer la valeur de la date
    	$dateexpedition=$rowcourrier[0]['DATEEXPEDITION'];
    	$datelimitereponse=$rowcourrier[0]['DATELIMITEREPONSE'];
    	$priorite=$rowcourrier[0]['ID_PRIORITE'];
    	if($priorite!=null){
    		//Maintenant que nous avons l'id de classification, nous souhaitons obtenir le libellé
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
    		//Maintenant que nous avons l'id de classification, nous souhaitons obtenir le libellé
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
    		//Maintenant que nous avons l'id de type, nous souhaitons obtenir le libellé
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

    //------------------------------FONCTIONS DE REFUS D'UN DOCUMENT --------------------------------------------
    
    //Fonction ajouter un formulaire de refus à la vue
    public function refusePopup()
    {
    	 
    	$form = new Application_Form_Refuse();
    	 
    	$this->view->refuseForm = $form;
    	 
    }
    //Fonction récupérant le formulaire de refus d'un document
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
    //Fonction traitant la requête à partir du formulaire de refus
    public function refusepdfAction()
    {
    
    	$request = $this->getRequest();
    	$form = $this->_getRefuseForm();
    		
    	//Récupérer les informations de la base de données
    	$db = Zend_Db_Table::getDefaultAdapter();
    
    	$id_document = $request->getParam('COURRIER_ID');
    
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    
    			//Ajout d'une image de validation au PDF
    				
    			//Récupérer l'image
    			$image = Zend_Pdf_Image::imageWithPath('../public/img/icons/ico_refused.png');
    				
    			//Compter le nombre de pages pdf
    			$count = count($this->pdf->pages)-1;
    				
    			//Obtenir la dernière page du pdf
    			$lastpage = $this->pdf->pages[$count];
    				
    			//Obtenir les largeur et longueur de la page
    			$width  = $lastpage->getWidth();
    			$height = $lastpage->getHeight();
    				
    			$imgWidth = $image->getPixelWidth();
    			$imgHeight = $image->getPixelHeight();
    				
    			//Dessiner une image sur la dernière page
    			$lastpage->drawImage($image, $width*0.45, $height*0.05, $width*0.45 + $imgWidth, $height*0.05 + $imgHeight);
    				
    			//Faire les changements associés dans la base de données ---------
    			$text_commentaire = $form->getValue('text_commentaire_refuse');
    				
    			//Récupérer l'ID du lieninterne entre notre utilisateur et le document
    			$sqlfind = 'SELECT ID_LIENINTERNE FROM LIENINTERNE WHERE ID_ENTITEDESTINATAIRE='.$this->user_ID.' AND ID_COURRIER ='.$id_document;
    			$stmtfind = $db->query($sqlfind);
    			$rowsfind = $stmtfind->fetchAll();
    			$id_lieninterne= $rowsfind[0]['ID_LIENINTERNE'];
    			$expediteur= $rowsfind[0]['ID_ENTITEEXPEDITEUR'];
    				
    			//Date : pour le test
    			if($text_commentaire!=null){
    				//Date
	    			$date = new Zend_Date();
	    			$month= $date->get(Zend_Date::MONTH);
	    			$day = $date->get(Zend_Date::DAY);
	    			$year=$date->get(Zend_Date::YEAR);
	    			$date = $day.'/'.$month.'/'.$year;
    				$commentaire = new Application_Model_DbTable_Commentaire();
    				$commentaire->ajouterCommentaire($id_document, $id_lieninterne, $text_commentaire, $date, '1');
    			}
    
    			//------------------------------------------------------------------------------------------------
    			//Archiver le lien qui existe entre l'utilisateur et le document
    			$sqlupdate='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=5 WHERE ID_LIENINTERNE='.$id_lieninterne;
    			$stmtupdate = $db->query($sqlupdate);
    
    			//Mettre à jour l'état de l'expéditeur pour qu'il voit le fichier dans son index de documents
    			$sqlupdatereceiver='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=1 WHERE ID_ENTITEDESTINATAIRE='.$expediteur;
    			$stmtupdatereceiver = $db->query($sqlupdatereceiver);
    
    			//------------------------------------------------------------------------------------------------
    			//Enregistrer le PDF
    			// Enregistrer le PDF en tant que nouveau fichier
    			$this->pdf->save('pdf/testSign.pdf');
    			//Redirigier l'action
    			$this->_helper->redirector('index','index');
    		}
    	}
    }
    
    //------------------------------FONCTIONS DE VALIDATION D'UN DOCUMENT -----------------------------------------
    
    //Fonction ajoutant un formulaire de validation à la vue
    public function validatePopup()
    {
    	 
    	$form = new Application_Form_Validate();
    	 
    	$this->view->validateForm = $form;
    	 
    }
    //Fonction traitant les requêtes issues du formulaire de validation
    public function validatepdfAction()
    {
    
    	//Récupérer les informations de la base de données
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$request = $this->getRequest();
    	$form = $this->_getValidateForm();
    	 
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    
    			//Ajout d'une image de validation au PDF
    				
    			//Récupérer l'image
    			$image = Zend_Pdf_Image::imageWithPath('../public/img/icons/ico_validated.png');
    			 
    			//Compter les pages pdf
    			$count = count($this->pdf->pages)-1;
    			 
    			//Récupérer la dernière page du PDF
    			$lastpage = $this->pdf->pages[$count];
    			 
    			//Obtenir les longueur et largeur de la page
    			$width  = $lastpage->getWidth();
    			$height = $lastpage->getHeight();
    			 
    			$imgWidth = $image->getPixelWidth();
    			$imgHeight = $image->getPixelHeight();
    			 
    			//Dessiner une image sur la dernière page
    			$lastpage->drawImage($image, $width*0.45, $height*0.05, $width*0.45 + $imgWidth, $height*0.05 + $imgHeight);
    			 
    			//Faire les changements associés dans la base de données
    			//Archiver le lien entre l'utilisateur et le document
    			$sqlupdate='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=3 WHERE ID_LIENINTERNE='.$id_lieninterne;
    			$stmtupdate = $db->query($sqlupdate);
    			 
    			//Chercher le prochain destinataire du document
    			$sqlnewreceiver='SELECT * FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE=5 AND ID_DOCUMENT='.$id_document.' ORDER BY DATECREATION';
    			$stmtreceiver = $db->query($sqlreceiver);
    			$rowsreceiver = $stmtreceiver->fetchAll();
    			$id_lieninternereceiver= $rowsreceiver[0]['ID_LIENINTERNE'];
    			 
    			if($rowsreceiver!=0){// S'il y a toujours quelqu'un dans le workflox
    				//Mettre à jour l'état du destinataire pour qu'il puisse voir le fichier dans son index des documents
    				$sqlupdatereceiver='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=1 WHERE ID_LIENINTERNE='.$id_lieninternereceiver;
    				$stmtupdatereceiver = $db->query($sqlupdatereceiver);
    			}
    			else{//tout le monde a approuvé le document, donc on peut l'envoyer au demandeur
    				$sqlfinddemandeur='SELECT * FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE=6 AND ID_DOCUMENT='.$id_document;
    				$stmtfinddemandeur = $db->query($sqlfinddemandeur);
    				$rowsfinddemandeur = $stmtfinddemandeur->fetchAll();
    				$id_lieninternedemandeur= $rowsfinddemandeur[0]['ID_LIENINTERNE'];
    
    				$sqlupdatedemandeur='UPDATE LIENINTERNE SET ID_ETATDESTINATAIRE=1 WHERE ID_LIENINTERNE='.$id_lieninternedemandeur;
    				$stmtupdatedemandeur = $db->query($sqlupdatereceiver);
    			}
    			 
    			//------------------------------------------------------------------------------------------------    			//Save the PDF
    			// Enregistrer le document en tant que nouveau fichier
    			$this->pdf->save('pdf/testSign.pdf');
    			 
    			//Rediriger l'action
    			$this->_helper->redirector('index','index');
    		}
    	}
    	 
    }
    //Fonction récupérant le formulaire de validation
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
    
    //------------------------------FONCTIONS D'AJOUT D'UN DESTINATAIRE --------------------------------------------
    
    //Fonction ajoutant le formulaire d'ajout de destinaire à la vue
    public function addPersonPopup($id_document, $user_ID, $db)
    {
    	$form = new Application_Form_Addperson();
    
    	$this->view->addpersonForm = $form;
    	$this->view->id_doc= $id_document;
    	 
    	// Montrer le workflow des personnes en attente du document ---------------------------------
    	//Récupérer l'id du commentaire lié au document
    	$sqldest = 'SELECT * FROM LIENINTERNE WHERE (ID_ETATDESTINATAIRE=1 OR ID_ETATDESTINATAIRE=2) AND ID_COURRIER ='.$id_document.'ORDER BY DATECREATION';
    	//Exécuter la requête et récupérer le résultat
    	$stmtdest = $db->query($sqldest);
    
    	$expediteur= array();
    	$destinataire=array();
    	$etat=array();
    	$date=array();
    
    	//Pour chaque document
    	while ($rowdest=$stmtdest->fetch()){
    		$idlien[]=$rowdest['ID_LIENINTERNE'];
    		$expediteur[]=$rowdest['ID_ENTITEEXPEDITEUR'];
    		$destinataire[]=$rowdest['ID_ENTITEDESTINATAIRE'];//Enregistrer l'ID
    		$etat[]=$rowdest['ID_ETATDESTINATAIRE'];//Enregistrer le titre
    		$date[]=$rowdest['DATECREATION'];//->toString();//Enregistrer la date
    	}
    	//Passer les paramètres à la vue
    	$this->view->idlien = $idlien;
    	$this->view->expediteur = $expediteur;
    	$this->view->destinataire = $destinataire;
    	$this->view->etat = $etat;
    	$this->view->date = $date;
    }
    //Fonction traitant les requêtes issues du formulaire
    public function addpersonpdfAction(){
    	 
    	$request = $this->getRequest();
    
    	//Récupérer les informations de la base de données
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$form = $this->_getAddPersonForm();
    	 
    	$id_document = $request->getParam('COURRIER_ID');
    	 
    	//Si la requête est postée
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    			 
    			$formData = $form->getValues();
    
    			$id_dest = $form->getValue('id_person');
    			$IRauteur = $form->getValue('IRauteur');
    			$type = $form->getValue('type');
    			//Date
    			$date = new Zend_Date();
    			$month= $date->get(Zend_Date::MONTH);
    			$day = $date->get(Zend_Date::DAY);
    			$year=$date->get(Zend_Date::YEAR);
    			$date = $day.'/'.$month.'/'.$year;
    			
    
    			$lieninterne = new Application_Model_DbTable_Lieninterne();
    			$lieninterne->ajouterLieninterne($id_document, $this->user_ID, $id_dest, $type, '1', 'N', $date, $IRauteur);
    
    			//return $this->_helper->redirector('showfile');
    
    		}
    	}
    }
    //Fonction récupérant le formulaire pour l'ajout d'un destinataire
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

