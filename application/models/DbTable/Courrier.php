<?php

class Application_Model_DbTable_Courrier extends Zend_Db_Table_Abstract
{

	protected $_name = 'COURRIER';
	protected $_primary = 'ID_COURRIER'; //Le nom de la cl� primaire (case sensitive)
	protected $_sequence = 'COURRIERSEQ';
	protected $_dependentTables = array('Application_Model_DbTable_Fichier');
	
	public function obtenirCourrier($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
	
	public function ajouterCourrier($id_typecourrier,$date)
    {
        $data = array(
			//'ID_COURRIER' => '2',
            'ID_TYPECOURRIER' => $id_typecourrier,
			'DATECREATION' => new Zend_Db_Expr("TO_DATE('$date', 'DD/MM/YYYY')"),
            'ID_GROUPECREATEUR' => '1',
			'IRDERNIEREMODIF' => 't',
            'ARCHIVE' => 'N',
			'EXPRESS' => 'N'
        );
        $id=$this->insert($data);
        return $id;
    }
}