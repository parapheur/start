<?php

/*
 * Fichier : Addafile.php
* Auteur : Mathilde de l'Hermuzière
* Créé : 09/11/2013
* Modifié : 11/11/2013
* 1.1 :  Mathilde de l'Hermuzière - création et modification
* 1.2 :  Mathilde de l'Hermuzière - modification
* Formulaire d'ajout de document 
* 
* Projet parapheur 2014
*/

class Application_Form_Addafile extends Zend_Form
{

    public function init()
    {
		// HTTP méthode : Post
    	$this->setAttrib('enctype', 'multipart/form-data');
    	$this->setMethod('post');
 
        // Le fichier à charger
        $this->addElement('file','upfile',array(
        		'label' => 'Document',
        		'required' => true,
        ));
        
        // Le titre du document
        $this->addElement('text', 'titre', array(
            'label'      => 'Titre',
            'required'   => false,
        	
        ));
		
 		// L'objet du document
         $this->addElement('text', 'object', array(
             'label'      => 'Objet du document',
             'required'   => false,
         ));
		
         // L'objet du document
         $this->addElement('select', 'type', array(
         		'label'      => 'Type',
         		'required'   => false,
         		'attribs' =>   array(
         				'id'=>'type_id',
         		),
         		'multioptions'   => array(
         				'administratif' => 'Administratif',
         				'projet' => 'Projet',
         				'divers' => 'Divers',
         		),
         ));
         
  		// L'identifiant de l'auteur du document
          $this->addElement('text', 'id_author', array(
              'label'      => 'ID Auteur',
              'required'   => false,
          ));
          
          // L'identifiant du destinataire 1
          $this->addElement('text', 'id_dest1', array(
          		'label'      => 'ID Destinataire 1',
          		'required'   => false,
          ));
		
          // L'identifiant du destinataire 2
          $this->addElement('text', 'id_dest2', array(
          		'label'      => 'ID Destinataire 2',
          		'required'   => false,
          ));
          
          // L'identifiant du destinataire 3
          $this->addElement('text', 'id_dest3', array(
          		'label'      => 'ID Destinataire 3',
          		'required'   => false,
          ));
		
		// Un bouton d'envoi
        $this->addElement('submit', 'ajouter', array(
            'ignore'   => true,
            'label'    => 'Ajouter',
        		
        ));
		
        // Bouton d'annulation
        $this->addElement('button', 'btncancel', array(
        		'ignore'   => true,
        		'label'    => 'Annuler',
        
        ));
        
  		// Et une protection anti CSRF
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}