<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use fylker as fylker;
use aviser as aviser;
use avis as avis;
use kommune as kommune;
use UKMNorge\Innslag\Media\Bilder\Bilde as UKMNorgeBilde;

class BilderController extends Controller
{
    public function lastnedAction($avis, $id)
    {
	    require_once('UKM/avis.class.php');
	    
	    $bilde = UKMNorgeBilde::getById( (int) $id );
	    
	    $TWIG = array('bilde' => $bilde);
   		$TWIG['mediegrupper'] = array('land'=>'UKM-festivalen', 'fylke'=>'Fylkesfestival', 'kommune'=>'Lokalmønstring');
   		$TWIG['avis'] = new avis( (int) $avis );
        return $this->render('MinPRBundle:Bilder:lastned.html.twig', $TWIG);
    }
}
