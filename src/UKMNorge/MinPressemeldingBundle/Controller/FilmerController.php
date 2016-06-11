<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use fylker as fylker;
use aviser as aviser;
use avis as avis;
use kommune as kommune;
use SQL as SQL;
use monstring_v2 as monstring_v2;
use innslag_v2 as innslag_v2;
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
		$TWIG['innslag'] = new innslag_v2( $b_id );
		return $this->render('MinPRBundle:Filmer:bygginn.html.twig', $TWIG);
	}
	
	public function liveAction($avis, $pl_id)
	{
		require_once('UKM/monstring.class.php');
		require_once('UKM/avis.class.php');
		
		$monstring = new monstring_v2( $pl_id );
		$wpServ = $this->get('min_pr.wordpress_option');
		$wpServ->setMonstring( $monstring->getId(), $monstring->getPath() );
		
		$live_link = $wpServ->getOption('ukm_live_link');
		$embed_code = $wpServ->getOption('ukm_live_embedcode');
		$perioder = $wpServ->getOption('ukm_hendelser_perioder');
		
		$TWIG['live_link'] = $live_link;
		$TWIG['embed_code'] = $embed_code;
	
		$TWIG['monstring'] = $monstring;
		$TWIG['avis'] = new avis( (int) $avis );
		return $this->render('MinPRBundle:Filmer:livestream.html.twig', $TWIG);
	}
}