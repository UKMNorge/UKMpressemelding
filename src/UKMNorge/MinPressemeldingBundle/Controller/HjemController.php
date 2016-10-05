<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use fylker as fylker;
use aviser as aviser;
use avis as avis;
use kommune as kommune;
use SQL as SQL;
use monstring as monstring;
use monstring_v2 as monstring_v2;
use innslag_v2 as innslag_v2;
use innslag_collection as innslag_collection;
use program as program;
use forestilling_v2 as forestilling_v2;
use forestillinger as forestillinger;
use artikler;
use artikkel;

class HjemController extends Controller
{
    public function oversiktAction($id)
    {
	    require_once('UKM/avis.class.php');
	    require_once('UKM/monstringer.class.php');
	    require_once('UKM/monstring.class.php');
	    require_once('UKM/innslag.class.php');
	    require_once('UKM/forestillinger.collection.php');
	    require_once('UKM/forestilling.class.php');
	    
	    $TWIG = array();

	    $TWIG['avis'] = new avis( $id );
	    $nedslagsfelt = $TWIG['avis']->getNedslagsfeltAsCSV();
	    if( UKM_HOSTNAME == 'ukm.dev' ) {
		    $nedslagsfelt[] = 2100;
		    $nedslagsfelt[] = 2101;
	    }
	    
	   	$season = UKM_HOSTNAME == 'ukm.dev' ? 2014 : date('Y');
	   	

	   	/* INFO OM FYLKET */
   		$fylke = $TWIG['avis']->getFylke();
   		$fylkepl = new \fylke_monstring_v2( $fylke->getId(), $season );
   		$TWIG['fylke']['monstring'] = $fylkepl->monstring_get();
   		
	    $pameldte = $TWIG['fylke']['monstring']->getInnslag()->getAll();
	    foreach( $pameldte as $innslag ) {
		    if( !in_array( $innslag->getKommune()->getId(), $nedslagsfelt ) ) {
			    continue;
			}
			$TWIG['fylke']['mine_innslag'][] = $innslag;
		}
   				
   		$wpServ = $this->get('min_pr.wordpress_option');
		$wpServ->setMonstring( $TWIG['fylke']['monstring']->getId(), $TWIG['fylke']['monstring']->getPath() );
		$TWIG['fylke']['pressemelding'] = $wpServ->getOption('pressemelding');
		
   		$TWIG['fylke']['mediegrupper'] = array('fylke'=>'Fylkesfestival', 'kommune'=>'Lokalmønstring');


		if( $TWIG['fylke']['monstring']->erFerdig() ) {

			$TWIG['vis_festival'] = true;

			$festivalen = new \landsmonstring( $season );
			$festivalpl = $festivalen->monstring_get();
			$TWIG['land']['monstring'] = new monstring_v2( $festivalpl->get('pl_id') );
			
			$pameldte = $TWIG['land']['monstring']->getInnslag()->getAll();
			foreach( $pameldte as $innslag ) {
			    if( !in_array( $innslag->getKommune()->getId(), $nedslagsfelt ) ) {
				    continue;
				}
				$TWIG['land']['mine_innslag'][] = $innslag;
			}
			
			$wpServ = $this->get('min_pr.wordpress_option');
			$wpServ->setMonstring( $TWIG['land']['monstring']->getId(), $TWIG['land']['monstring']->getPath() );
			$TWIG['land']['pressemelding'] = $wpServ->getOption('pressemelding');
			
			$TWIG['land']['mediegrupper'] = array('land'=>'UKM-festivalen', 'fylke'=>'Fylkesfestival', 'kommune'=>'Lokalmønstring');
		} else {
			$TWIG['vis_festival'] = false;
		}

		return $this->render('MinPRBundle:Hjem:oversikt.html.twig', $TWIG);
	
    }
}
