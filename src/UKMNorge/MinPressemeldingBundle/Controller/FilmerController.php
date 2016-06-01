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

class FilmerController extends Controller
{
    public function bygginnAction($avis, $b_id)
    {
	    require_once('UKM/avis.class.php');
	    require_once('UKM/innslag.class.php');
	    
   		$TWIG['mediegrupper'] = array('land'=>'UKM-festivalen', 'fylke'=>'Fylkesfestival', 'kommune'=>'Lokalmønstring');
   		$TWIG['avis'] = new avis( (int) $avis );
   		$TWIG['innslag'] = new innslag( $b_id );
        return $this->render('MinPRBundle:Filmer:bygginn.html.twig', $TWIG);
    }
}
