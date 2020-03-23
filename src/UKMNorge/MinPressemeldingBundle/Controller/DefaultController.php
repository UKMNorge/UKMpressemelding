<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use aviser as aviser;
use UKMNorge\Geografi\Fylker;

class DefaultController extends Controller
{
    public function indexAction()
    {
	   	require_once('UKM/aviser.class.php');
	   	
	   	$TWIG = array();
	    
		$fylker = Fylker::getAll();
		foreach( $fylker as $fylke ) {
		    $aviser = new aviser();
		    $TWIG['aviser'][ $fylke->getId() ] = $aviser->getAllByFylke( $fylke->getId() );
		    $TWIG['fylker'][ $fylke->getId() ] = $fylke;
		}
			    
        return $this->render('MinPRBundle:Default:index.html.twig', $TWIG);
    }
}
