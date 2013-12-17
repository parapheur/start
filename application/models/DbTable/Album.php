<?php

class Application_Model_DbTable_Album extends Zend_Db_Table_Abstract
{

    protected $_name = 'ALBUMS';
    protected $_primary = 'ID';
    
    public function obtenirAlbum($id)
    {
    	$id = (int)$id;
    	$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
}

