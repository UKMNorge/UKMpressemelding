<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UKMNorge\Avis\Aviser;
use UKMNorge\Geografi\Fylker;
require_once('UKM/Autoloader.php');

class DefaultController extends Controller
{
    public function indexAction()
    {
	   		   	$TWIG = array();
	    
		$fylker = Fylker::getAll();
		foreach( $fylker as $fylke ) {
		    $aviser = new Aviser();
		    $TWIG['aviser'][ $fylke->getId() ] = $aviser->getAllByFylke( $fylke->getId() );
		    $TWIG['fylker'][ $fylke->getId() ] = $fylke;
		}
			    
        return $this->render('MinPRBundle:Default:index.html.twig', $TWIG);
    }
}
