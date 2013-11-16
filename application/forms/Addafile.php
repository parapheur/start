<?php

/*
 * File : Addafile.php
* Author : Mathilde de l'Hermuzière
* Created : 09/11/2013
* Modified : 11/11/2013
* 1.1 :  Mathilde de l'Hermuzière - creation and modification
* 1.2 :  Mathilde de l'Hermuzière - modification
* Form that permits to add a file 
* 
* Projet parapheur 2014
*/

class Application_Form_Addafile extends Zend_Form
{

    public function init()
    {
		// HTTP method of sending form : Post
    	$this->setAttrib('enctype', 'multipart/form-data');
    	$this->setMethod('post');
 
        // The file to upload
        $this->addElement('file','upfile',array(
        		'label' => 'Document',
        		//'destination'=> 'C:\Windows\Temp',
        		'required' => true,
        ));
        
        // Le titre du document
        $this->addElement('text', 'titre', array(
            'label'      => 'Titre',
            'required'   => true,
        	
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
          
//         $this->addElement('text', 'date_limite', array(
//         	'required'   => false,
//         	'validators'  => array (
//         			array('date', false, array('MM/dd/yyyy'))
//         	),
//         	'label'      => 'Date Limite Réponse',
//         	'class'      => 'form-date'
//         ));
           
//   		// La classification
//         $this->addElement('text', 'classification', array(
//             'label'      => 'Classification',
//             'required'   => false,
//         ));
          
//           // L'objet du document
//         $this->addElement('text', 'code_externe', array(
//         	'label'      => 'Code Externe',
//         	'required'   => false,
        	
//         ));
          
//         // L'objet du document
//         $this->addElement('text', 'message', array(
//         	'label'      => 'Message Usage Interne',
//         	'required'   => false,
//         	array( 'tag' => 'br', 'placement' => 'prepend'),
//         ));
		
		// Un bouton d'envoi
        $this->addElement('submit', 'ajouter', array(
            'ignore'   => true,
            'label'    => 'Ajouter',
        		
        ));
		
        // Cancel button
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