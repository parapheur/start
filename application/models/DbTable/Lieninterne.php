<?php

class Application_Model_DbTable_Lieninterne extends Zend_Db_Table_Abstract
{

    protected $_name = 'LIENINTERNE';
	protected $_primary = 'ID_LIENINTERNE'; //Le nom de la clé primaire (case sensitive)
	
	public function obtenirLieninterne($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }

}

