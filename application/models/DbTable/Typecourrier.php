<?php

class Application_Model_DbTable_Typecourrier extends Zend_Db_Table_Abstract
{

    protected $_name = 'T_TYPECOURRIER';
	protected $_primary = 'ID_TYPECOURRIER'; //Le nom de la clé primaire (case sensitive)
	
	public function obtenirTypecourrier($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }

}

