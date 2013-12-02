<?php

/*
 * Fichier : Comment.php
* Auteur : Mathilde de l'Hermuzière
* Créé : 17/11/2013
* Modifié : 17/11/2013
* 1.1 :  Mathilde de l'Hermuzière - création et modification
* Formulaire de recherche 
* 
* Projet parapheur 2014
*/

class Application_Form_Comment extends Zend_Form
{
	public function init()
	{
		// méthode HTTP : post
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setMethod('post');

		 
	}
}