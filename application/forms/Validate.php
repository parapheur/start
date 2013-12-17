<?php

/*
 * Fichier : Validate.php
* Auteur : Hina Tufail
* Créé : 16/11/2013
* Modifié : 16/11/2013
* 1.1 :  Hina Tufail - création et modification
* Formulaire de validation d'un document
*
* Projet parapheur 2014
*/

class Application_Form_Validate extends Zend_Form
{

    public function init()
    {
    	// méthode HTTP : post
    	$this->setAttrib('enctype', 'multipart/form-data');
    	$this->setMethod('post');
    	
    	// Valider - bouton de type submit
    	$this->addElement('submit', 'valider', array(
    			'ignore'   => true,
    			'label'    => 'Valider',
    			'class'		=> 'green-button on-right'
    	
    	));  	
    }
}

