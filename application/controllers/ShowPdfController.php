<?php

class ShowPdfController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	 	$this->_helper->layout->disableLayout();
    		$this->_helper->viewRenderer->setNoRender(true);
    	
		$fileName = '..\docs\debuter-avec-zend-framework.pdf';
		//$fileName = 'C:\Users\Hina\Desktop\LettreMotivation_PassageJury_4A5A_HinaTufail.pdf';
		
    	$pdf = new Zend_Pdf();
    	$pdf = Zend_Pdf::load($fileName,null,true);
    	 
    	$this->getResponse()->setHeader('Content-type', 'application/pdf', true);
    	$this->getResponse()->setHeader('Content-disposition','inline;filename='.$module.'_'.$m_no.'.pdf', true);
    	
    	$this->getResponse()->setHeader('Cache-Control: no-cache, must-revalidate');
    	$this->getResponse()->setHeader('Content-Transfer-Encoding', 'binary', true);
    	$this->getResponse()->setHeader('Last-Modified', date('r'));
    	 
    	$this->getResponse()->clearBody();
    	$this->getResponse()->sendHeaders();

    	$this->getResponse()->setBody($pdf->render());
    	
    }
    
    public function showfileAction()
    {
    	
    	$this->_helper->layout->disableLayout();
    }
    
    public function showmetaAction()
    {
    	 
    	$this->_helper->layout->disableLayout();
		$courrier = new Application_Model_DbTable_Courrier;
		$this->view->courrier = $courrier->fetchAll();

    }
     
    public function viewmetaAction()
    {
    	$fileName = '..\docs\debuter-avec-zend-framework.pdf';
    	$pdf = new Zend_Pdf();
    	$pdf = Zend_Pdf::load($fileName,null,true);
    
    	$metaValues = array('Title'       => '',
    			'Author'      => '',
    			'Subject'      => '',
    			'Keywords'     => '',
    			'Creator'      => '',
    			'Producer'     => '',
    			'CreationDate' => '',
    			'ModDate'      => '',
    	);
    	 
    	$pdf->properties['Title'] = 'Document de parapheur numerique';
    	$pdf->properties['Author'] = 'Hina';
    	$pdf->properties['Creator'] = 'Hina';
    	$pdf->properties['Subject'] = 'Sujet';
    	$pdf->properties['Keywords'] = 'Mots clés';
    	$pdf->properties['Producer'] = 'Producteur';
    	$pdf->properties['CreationDate'] = '31/10/2013';
    	 
    
    	foreach ($metaValues as $meta => $metaValue) {
    		if (isset($pdf->properties[$meta])) {
    			$metaValues[$meta] = $pdf->properties[$meta];
    		} else {
    			$metaValues[$meta] = '';
    		}
    	}
   	

    	$this->view->assign('file',$fileName);
    	$this->view->assign('meta',$metaValues);
    	 
    }
    


}

