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
    	$conBDD="Base de donn�es connect�e !";
    	try {
    		$db = Zend_Db::getDefaultAdapter;
    		$db->getConnection();
    	} catch (Zend_Db_Adapter_Exception $e) {
    		// perhaps a failed login credential, or perhaps the RDBMS is not running
    		$conBDD='db error :'.$e->getMessage();
    	} catch (Zend_Exception $e) {
    		// perhaps factory() failed to load the specified Adapter class
    		$conBDD='db error :'.$e->getMessage();
    	}
    	$this->view->$conBDD;
    }

    public function diagnosticAction()
    {
    }

    public function connexionbddAction()
    {
        // action body
        
    }


}



