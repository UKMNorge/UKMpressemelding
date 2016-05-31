<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use fylker as fylker;
use aviser as aviser;
use avis as avis;
use kommune as kommune;
use SQL as SQL;
use monstring as monstring;
use innslag as innslag;
use bilder;
use bilde;
use bilde_storrelse;
use wp_author;

class BilderController extends Controller
{
    public function lastnedAction($avis, $id)
    {
	    require_once('UKM/bilder.class.php');
	    require_once('UKM/avis.class.php');
	    
	    $bilde = new bilde( (int) $id );
	    
	    $TWIG = array('bilde' => $bilde);
   		$TWIG['mediegrupper'] = array('land'=>'UKM-festivalen', 'fylke'=>'Fylkesfestival', 'kommune'=>'Lokalmønstring');
   		$TWIG['avis'] = new avis( (int) $avis );
        return $this->render('MinPRBundle:Bilder:lastned.html.twig', $TWIG);
    }
}
