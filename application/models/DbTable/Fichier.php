<?php

class Application_Model_DbTable_Fichier extends Zend_Db_Table_Abstract
{

    protected $_name = 'FICHIER';
	protected $_primary = 'ID_FICHIER'; //Le nom de la clé primaire (case sensitive)
	protected $_sequence = 'FICHIERSEQ';
	protected $_dependentTables = array('Application_Model_DbTable_Contenu');
	protected $_referenceMap    = array(
			'Courrier' => array(
					'columns'           => 'ID_COURRIER',
					'refTableClass'     => 'Application_Model_DbTable_Courrier',
					'refColumns'        => 'ID_COURRIER'
			)
	);
	
	public function obtenirFichier($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
    
	public function ajouterFichier($id_courrier, $id_typefichier, $taille, $nomorigine)
    {
        $data = array(
			'ID_COURRIER' => $id_courrier,
            'ID_TYPEFICHIER' => $id_typefichier,
			'TAILLE' => $taille,
            'SIGNATUREA' => 'test',
			'SIGNATUREB' => 'test',
			'NOMORIGINE' => $nomorigine
        );
        $id=$this->insert($data);
        return $id;
    }
}

