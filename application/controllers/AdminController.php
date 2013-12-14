<?php

/*
 * Fichier : AdminController.php
* Auteur : 
* Créé : 
* Modifié : 16/11/2013
* 1.1 : - Creation
*
* Controller qui contrôle la vue de l'administration
*
* Projet parapheur 2014
*/

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {    
    	
    }

    public function diagnosticAction()
    {
    	$conBDD="Base de données connectée !";
    	try {
    		$db = Zend_Db_Table::getDefaultAdapter();
    		$db->getConnection();
    	} catch (Zend_Db_Adapter_Exception $e) {
    		// perhaps a failed login credential, or perhaps the RDBMS is not running
    		$conBDD='Problème de connexion :'.$e->getMessage();
    	} catch (Zend_Exception $e) {
    		// perhaps factory() failed to load the specified Adapter class
    		$conBDD='Problème de connexion :'.$e->getMessage();
    	}
    	$this->view->conBDD=$conBDD;
    }

    public function connexionbddAction()
    {
        // action body
        
    }


}



