<?php

/*
* Fichier : AddafileController.php
* Auteur : Mathilde De l'Hermuzière
* Crée : 09/11/2013
* Modifié : 16/11/2013
* 1.1 : Mathilde de l'Hermuzière - Création et modification pour l'ajout d'un clob
* 1.2 : Hina Tufail - modification
* 1.3: Mathilde de l'Hermuziere - redirection dans un filer
* 
* Controller d'une vue permettant l'ajout d'un document dans la base de données
*
* Projet parapheur 2014
*/

class AddafileController extends Zend_Controller_Action
{

    public function init()
    {
    }

    //Action (test) pour récupérer un clob depuis la base de données
    public function indexAction()
    {
    }

    //Action permettant l'ajout d'un document sous le format clob dans la base de données
	public function signAction()
    {
    	//Comme nous n'avons pas Active Directory, nous supposons que notre user ID est 6
    	$user_ID=6;
    	
        $request = $this->getRequest();
        $form    = new Application_Form_Addafile();
 
        if ($this->getRequest()->isPost()) {
             if ($form->isValid($request->getPost())) {
              	
             	//Cr�er les tables dans la base de donn�es
             	$id_typecourrier=1;
             	$id_typefichier=1; //is a PDF
             	$taille=500;
             	$title=$form->getValue('titre');
             	
             	//R�cup�ration des tables Courrier, Fichier and Contenu (cr�ation d'objet)
             	$courrier = new Application_Model_DbTable_Courrier();
             	$fichier = new Application_Model_DbTable_Fichier();
             	$contenu = new Application_Model_DbTable_Contenu();
             		
             	$date = $this->_getDate();
             	$id_courrier=  $courrier->ajouterCourrier($id_typecourrier, $date);	
             	$id_fichier=$fichier->ajouterFichier($id_courrier, $id_typefichier, $taille, $title);
             	
             	//R�cup�rer le fichier t�l�charg�
			 	$upload = new Zend_File_Transfer_Adapter_Http();
			    $upload->addFilter('Rename', array(
			    'target' => APPLICATION_PATH.'/../public/pdf/'.$id_courrier.'.pdf',
			    'overwrite' => true));
			    try { //be sure to call receive() before getValues()
			    	$upload->receive();
			 	} catch (Zend_File_Transfer_Exception $e) {
			 	    $e->getMessage();
			 	}
			 	
			 	//On r�cup�re les autres champs du formulaire
			 	$values= $form->getValues();
				$author = $form->getValue('id_author');
				$des1 = $form->getValue('id_dest1');
				$des2 = $form->getValue('id_dest2');
				$des3 = $form->getValue('id_dest3');
				echo $author;
				echo $des1;
				echo $des2;
				echo $des3;
				//On va ajouter des liens avec des acteurs du documents.
				$lieninterne = new Application_Model_DbTable_Lieninterne();

				//On r�cup�re la date d'aujourd'hui
				$date = $this->_getDate();
				
				$validator = new Zend_Validate_Int();//Permet de s'assurer que les ID sont bien des entiers
				$min=0;
				$validatorPositive = new Zend_Validate_GreaterThan($min);//Permet de s'assurer que les ID sont bien des entiers positifs
				$exist=false; //Ce boolean nous permet de savoir si le premier destinataire a bien �t� ajout�
				$en_cours='1'; // Le destinataire est le premier il aura donc acc�s directement au document
				$en_attente='5'; // Le destinataire n'est pas le premier il est donc dans la file d'attente
				$demandeur='6';//Dans la BDD on suppose que l'�tat d'un demandeur dans lieninterne est 6.
							
				if($author!=null && $author!=""){
					
					
					if (($validator->isValid($author)) && ($validatorPositive->isValid($author))) {
						//on sp�cifie que l'auteur est le demandeur
						$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $author, $demandeur, $date);
						$exist=true;
					}
					else{//le champs auteur n'est pas rempli correctement, on consid�re que l'utilisateur est le demandeur
						$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $user_ID, $demandeur, $date);
					}					
				}
				else{//le champs auteur est vide, on consid�re que l'utilisateur est le demandeur
					
					$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $user_ID, $demandeur, $date);
				}
				
				if($des1!=null && $des1!=""){

					if (($validator->isValid($des1)) && ($validatorPositive->isValid($des1))) {
						$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $des1, $en_cours, $date);
					}
				}
				
				if($des2!=null && $des2!=""){
						
					if (($validator->isValid($des2)) && ($validatorPositive->isValid($des2))) {
						if($exist==true){
							$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $des2, $en_attente, $date);
						}
						else{//des1 is not filled in in the form
							$exist==true;
							$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $des2, $en_cours, $date);
						}
					}
				}
				
				if($des3!=null && $des3!=""){
				
					if (($validator->isValid($des3)) && ($validatorPositive->isValid($des3))) {
						if($exist==true){
							$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $des3, $en_attente, $date);
						}
						else{//des1 and des2 are not filled in in the form
							$exist==true;
							$lieninterne->ajouterLieninterne($id_courrier, $user_ID, $des3, $en_cours, $date);
						}
					}
				}
				//$contenu->ajouterContenu($id_fichier, $pdfString);
				$this->_helper->redirector('index', 'index');
             }
        }
 
        $this->view->form = $form;
    }
    
    private function _getDate(){
    	
    	$date = new Zend_Date();
    	$month= $date->get(Zend_Date::MONTH);
    	$day = $date->get(Zend_Date::DAY);
    	$year=$date->get(Zend_Date::YEAR);
    	$date = $day.'/'.$month.'/'.$year;
    	return $date;
    }

}

