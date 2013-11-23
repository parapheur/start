<?php

class Application_Model_DbTable_Lieninterne extends Zend_Db_Table_Abstract
{

    protected $_name = 'LIENINTERNE';
	protected $_primary = 'ID_LIENINTERNE'; //Le nom de la clé primaire (case sensitive)
	protected $_sequence = 'CANALSEQ';
	
	public function obtenirLieninterne($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }

    public function ajouterLieninterne($id_courrier, $id_exped, $id_dest, $id_type, $id_etat, $visible, $date, $ir_auteur)
    {
    	$data = array(
    			'ID_COURRIER' => $id_courrier,
    			'ID_ENTITEEXPEDITEUR' => $id_exped,
    			'ID_ENTITEDESTINATAIRE' => $id_dest,
    			'ID_TYPELIENENTITE' => $id_type,
    			'ID_ETATDESTINATAIRE' => $id_etat,
    			'VISIBLE' => $visible,
    			'DATECREATION' => new Zend_Db_Expr("TO_DATE('06/05/2012', 'DD/MM/YYYY')"),
    			'IRAUTEUR' => $ir_auteur
    	);
    	$this->insert($data);
    }
}

