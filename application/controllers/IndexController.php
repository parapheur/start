<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    	//As we do not have Active Directory, we assume that our user ID is 6
    	$user_ID=6;
    	//We initialise a variable that will count our number of documents
    	$number_doc=0;
    	//Get the database infos
    	$db = Zend_Db_Table::getDefaultAdapter();
    	
    	
//     	$sql=$db->select()
//     				->from('LIENINTERNE','ID_COURRIER')
//     				->where('ID_ETATDESTINATAIRE=1')
//     				->where('ID_ENTITEDESTINATAIRE=?',$user_ID);

    	//$sql=$db->select()
    		//	->from('FICHIER','NOMORIGINE')
    			//->where('ID_COURRIER ='.new Zend_db_Expr($subSelect));
    			
    	//     	if ($this->_request->isPost()) {
    	//     		$data = $this->_request->getPost();
    	//     		Zend_Debug::dump($data);
    	//     	}

    	//Find all documents related to our users
    	$sql = 'SELECT ID_COURRIER FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE = 1 AND ID_ENTITEDESTINATAIRE = '.$user_ID;
        
    	//Get the result
    	$stmt = $db->query($sql);

		$titre= array();
		$date=array();
		$id_document=array();
		
 		while ($row=$stmt->fetch()){//For each documents
 			$sql2='SELECT DATECREATION FROM COURRIER WHERE ID_COURRIER = '.$row[ID_COURRIER];//Get the creation date
 			$stmt2 = $db->query($sql2);
 			$row2 = $stmt2->fetch();
			
 			$sql3='SELECT NOMORIGINE FROM FICHIER WHERE ID_COURRIER = '.$row[ID_COURRIER];//get the title
 			$stmt3 = $db->query($sql3);
 			$row3 = $stmt3->fetch();
 			
 			$number_doc++;//Increment the number of documents
  			$id_document[]=$row[ID_COURRIER];//Save the ID Value
  			$titre[]=$row3[NOMORIGINE];//Save the title Value
  			$date[]=$row2[DATECREATION];//Save the date value
 		}
 		//Pass values to view
		$this->view->id_document=$id_document;
		$this->view->number_doc =$number_doc;
		$this->view->titre =$titre;
		$this->view->date =$date;
    	
    }
    
    public function addfileAction()
    {
    }

}

