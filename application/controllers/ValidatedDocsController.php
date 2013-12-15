<?php

/*
 * Fichier : ValidateDocsController.php
* Auteur : Hina Tufail
* Créé : 14/12/2013
* 1.1 :  Hina Tufail - création
* 1.2 :  Clément Mouraud - impression
* Controller des documents qui ont été validés et donc renvoyés au demandeur
*
* Projet parapheur 2014
*/

class ValidatedDocsController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->etat_valide=3;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
    	$user_ID = $request->getParam('USER');
        //Nous initialisons une variable qui comptera notre nombre de documents
    	$number_doc=0;
    	//Récupérer les données de la base de données
    	$db = Zend_Db_Table::getDefaultAdapter();

    	//Récupérer tous les documents reliés à notre utilisateur
    	$sql = 'SELECT ID_COURRIER FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE = '.$this->etat_valide.' AND ID_ENTITEEXPEDITEUR = '.$user_ID;
        
    	//Exécuter la requête et récupérer le résultat
    	$stmt = $db->query($sql);

		$titre= array();
		$date=array();
		$id_document=array();
		
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
 		//Passer les paramètres à la vue
		$this->view->id_document=$id_document;
		$this->view->titre =$titre;
		$this->view->date =$date;
    }
    
    public function downloadAction()
    {
    	//On récupère l'ID du document que nous souhaitons afficher à partir de l'indexController
    	$request = $this->getRequest();
    	$id_document = $request->getParam('COURRIER_ID');
    	//Fichier PDF qui est utilisé = useful for signature and images
    	$filePath = APPLICATION_PATH.'/../public/pdf/'.$id_document.'.pdf';
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	 
    	header("Cache-Control: public");
    	header("Content-Description: File Transfer");
    	header('Content-disposition: attachment; filename='.basename($filePath));
    	header("Content-Type: application/pdf");
    	header("Content-Transfer-Encoding: binary");
    	header('Content-Length: '. filesize($filePath));
    	readfile($filePath);
    	exit;
    	 
    }

}

