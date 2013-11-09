<?php

class Application_Model_DbTable_Contenu extends Zend_Db_Table_Abstract
{

    protected $_name = 'CONTENU';
	protected $_primary = 'ID_FICHIER'; //Le nom de la clé primaire (case sensitive)
	protected $_referenceMap    = array(
			'Fichier' => array(
					'columns'           => 'ID_FICHIER',
					'refTableClass'     => 'Application_Model_DbTable_Fichier',
					'refColumns'        => 'ID_FICHIER'
			)
	);
	
	public function obtenirContenu($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }

    public function ajouterContenu($id_fichier, $contenu)
    {
    	$sql = "INSERT INTO CONTENU(ID_FICHIER,CONTENU) VALUES ($id_fichier, EMPTY_CLOB())
        RETURNING CONTENU INTO :CONTENU_loc";
    	$conn = oci_connect("DBA_PARAPHEUR","12345678","XE");
    	$stmt = oci_parse($conn, $sql);
    	// Creates an "empty" OCI-Lob object to bind to the locator
    	$clob = oci_new_descriptor($conn, OCI_D_LOB);
    	
    	// Bind the returned Oracle LOB locator to the PHP LOB object
    	oci_bind_by_name($stmt, ":CONTENU_loc", $clob, -1, OCI_B_CLOB);
    	
    	// Execute the statement using , OCI_DEFAULT - as a transaction
    	oci_execute($stmt, OCI_DEFAULT)
    	or die ("Unable to execute query\n");
    	
    	// Now save a value to the clob
    	if ( !$clob->save($contenu) ) {
    	
    		// On error, rollback the transaction
    		oci_rollback($conn);
    	
    	} else {    	
    		// On success, commit the transaction
    		oci_commit($conn);  	
    	}
    }
}

