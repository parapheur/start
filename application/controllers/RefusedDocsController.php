<?php

class RefusedDocsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
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
    	$sql = 'SELECT ID_COURRIER FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE = 4 AND ID_ENTITEEXPEDITEUR = '.$user_ID;
        
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


}

