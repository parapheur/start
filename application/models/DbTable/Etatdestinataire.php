<?php

class Application_Model_DbTable_Etatdestinataire extends Zend_Db_Table_Abstract
{

    protected $_name = 'T_ETATDESTINATAIRE';
	protected $_primary = 'ID_ETATDESTINATAIRE'; //Le nom de la clé primaire (case sensitive)
	
	public function obtenirEtatdestinataire($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
}

