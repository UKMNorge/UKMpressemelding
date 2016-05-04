<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use fylker as fylker;
use aviser as aviser;

class DefaultController extends Controller
{
    public function indexAction()
    {
	   	require_once('UKM/aviser.class.php');
	   	require_once('UKM/fylker.class.php');
	   	
	   	$TWIG = array();
	    
		$fylker = fylker::getAll();
		foreach( $fylker as $fylke ) {
		    $aviser = new aviser();
		    $TWIG['aviser'][$fylke->name] = $aviser->getAllByFylke( $fylke->id );
		}
			    
        return $this->render('MinPRBundle:Default:index.html.twig', $TWIG);
    }
}
