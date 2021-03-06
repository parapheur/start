<?php

/*
* Fichier : IndexController.php
* Auteur : Hina Tufail
* Créé : 19/10/2013
* Modifié : 16/11/2013
* 1.1 :  Hina Tufail - création
* 1.2 : Mathilde de l'Hermuzière - modification
* 1.3 : Hina Tufail - modification
* 1.4 : Mathilde de l'Hermuzière - modification
* 1.5 : Hina Tufail - modification - 20/11/2013
* 1.6 : Hina Tufail - modification - 15/12/2013
*
* Controller du la page d'accueil (index des documents)
*
* Projet parapheur 2014
*/

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->etat_encours=1;
    	$this->etat_surtablette=2;
    	$this->etat_valide=3;
    	$this->etat_refuse=4;
    	$this->etat_enattente=5;
    	$this->etat_demandeur=6;
    }

    public function indexAction()
    {

    	//Mettre en place le message du succès de la signature s'il y a eu une signature
    	$this->signSuccessMessage();
    	//Mettre en place le message du validation s'il y a eu une validation
    	$this->validationMessage();
    	
    	//Comme nous n'avons pas Active Directory, nous supposons que notre user ID est 6
    	$user_ID=6;
    	//Nous initialisons une variable qui comptera notre nombre de documents
    	$number_doc=0;
    	//Récupérer les données de la base de données
    	$db = Zend_Db_Table::getDefaultAdapter();
		
    	$request = $this->getRequest();
    	$name_document = $request->getParam('SEARCH');
    	    	
		$titre= array();
		$date=array();
		$id_document=array();
		
		if($name_document!=null){
			$sql = "SELECT l.ID_COURRIER FROM LIENINTERNE l, FICHIER f WHERE l.ID_ETATDESTINATAIRE = ".$this->etat_encours."
							AND l.ID_ENTITEDESTINATAIRE = ".$user_ID."
							AND f.NOMORIGINE LIKE '%".$name_document."%' AND l.ID_COURRIER=f.ID_COURRIER(+)";
		}
		else{
			$sql = 'SELECT ID_COURRIER FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE = '.$this->etat_encours.' AND ID_ENTITEDESTINATAIRE = '.$user_ID;
		}
		//Exécuter la requête et récupérer le résultat
		$stmt = $db->query($sql);
		
		//Pour chaque document
 		while ($row=$stmt->fetch()){
 			$sql2='SELECT DATECREATION FROM COURRIER WHERE ID_COURRIER = '.$row['ID_COURRIER'];//Récupérer la date de création
 			$stmt2 = $db->query($sql2);
 			$row2 = $stmt2->fetch();
			
 			$sql3='SELECT NOMORIGINE FROM FICHIER WHERE ID_COURRIER = '.$row['ID_COURRIER'];//Récupérer le titre
 			$stmt3 = $db->query($sql3);
 			$row3 = $stmt3->fetch();
 			
 			$number_doc++;//Incrémenter le nombre de documents
  			$id_document[]=$row['ID_COURRIER'];//Enregistrer l'ID
  			$titre[]=$row3['NOMORIGINE'];//Enregistrer le titre
  			$date[]=$row2['DATECREATION'];//Enregistrer la date
 		}
 		
 		//Nombre de documents refusés
 		$sqlRef = 'SELECT ID_COURRIER FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE = '.$this->etat_refuse.' AND ID_ENTITEEXPEDITEUR = '.$user_ID;
 		$stmtRef = $db->query($sqlRef);
 		$rows = $stmtRef->fetchAll();
 		$countRefused = count($rows);
 		
 		//Nombre de documents validés
 		$sqlVal = 'SELECT ID_COURRIER FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE = '.$this->etat_valide.' AND ID_ENTITEEXPEDITEUR = '.$user_ID;
 		$stmtVal = $db->query($sqlVal);
 		$rows = $stmtVal->fetchAll();
 		$countValidated = count($rows);
 		
 		//Passer les paramètres à la vue
		$this->view->id_document=$id_document;
		$this->view->number_doc =$number_doc;
		$this->view->titre =$titre;
		$this->view->date =$date;
    	$this->view->user_id=$user_ID;
    	$this->view->number_doc_refuse = $countRefused;
    	$this->view->number_doc_accept = $countValidated;
    }

    private function signSuccessMessage()
    {
    	
    	$signSuccess = '';
    	$signError =  '';
    	$request = $this->getRequest();
    	$success = $request->getParam('SUCCESS');
    	if($success == '1'){
    		//Message de succès
    		$signSuccess='Félicitations ! Votre document a été signé !';
    	}
    	else if($success == '0'){
    		//Message d'erreur de la signature
    		$signError='Il y a eu une erreur dans la signature !';
    	}
    	 
    	$this->view->sucessMessage = $signSuccess;
    	$this->view->errorMessage = $signError;
    }
    
    private function validationMessage(){
    	$signSuccess = '';

    	$request = $this->getRequest();
    	$success = $request->getParam('VALID');
    	if($success == '1'){
    		//Message de succès
    		$signSuccess='Vous avez validé votre document avec succès !';
    	}

    	$this->view->sucessMessage = $signSuccess;
    }
}



