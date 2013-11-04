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
    	
    	$fileName = 'C:\Users\Hina\Downloads\minicdcparapheur.pdf';
    	$pdf = new Zend_Pdf();
    	$pdf = Zend_Pdf::load($fileName,null,true);
    	/* 
    	// Create new Style
    	$style = new Zend_Pdf_Style();
    	$style->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0.9));
    	$style->setLineColor(new Zend_Pdf_Color_GrayScale(0.2));
    	$style->setLineWidth(3);
    	$style->setLineDashingPattern(array(3, 2, 3, 4), 1.6);
    	$fontH = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    	$style->setFont($fontH, 12);

    	try {
    		// Create new image object
    		$imageFile = dirname(__FILE__) . '/../../public/img/infoIcon.png';
    		$stampImage = Zend_Pdf_Image::imageWithPath($imageFile);
    	} catch (Zend_Pdf_Exception $e) {
    		// Example of operating with image loading exceptions.
    		if ($e->getMessage() != 'Image extension is not installed.' &&
    		$e->getMessage() != 'JPG support is not configured properly.') {
    			throw $e;
    		}
    		$stampImage = null;
    	}
    	
    	foreach ($pdf->pages as $page){
    		$page->saveGS()
    		->setAlpha(0.25)
    		->setStyle($style);
    		 
    		$page->saveGS();
    		
    		if ($stampImage != null) {
    			list($width, $height, $type, $attr) = getimagesize($imagePath);
    			$page->drawImage($stampImage, 50, 70, $width, $height);
    		}
    		$page->restoreGS();
    		 
    		$page->drawText('Modified by Zend Framework!', 150, 0)
    		->restoreGS();
    	}*/
    	 
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
    	$fileName = 'C:\Users\Hina\Downloads\minicdcparapheur.pdf';
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

