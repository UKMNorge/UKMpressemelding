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

class HjemController extends Controller
{
    public function oversiktAction($id)
    {
	    require_once('UKM/avis.class.php');
	    require_once('UKM/monstringer.class.php');
	    require_once('UKM/monstring.class.php');
	    require_once('UKM/innslag.class.php');
	    
	    $TWIG = array();

	    $TWIG['avis'] = new avis( $id );
	    
	    
	   	$season = UKM_HOSTNAME == 'ukm.dev' ? 2014 : date('Y');
	   	
	    $festivalen = new \landsmonstring( $season );
	    $TWIG['festivalen'] = $festivalen->monstring_get();
	    
	    $nedslagsfelt = $TWIG['avis']->getNedslagsfeltAsCSV();
	    if( UKM_HOSTNAME == 'ukm.dev' ) {
		    $nedslagsfelt[] = 2100;
		    $nedslagsfelt[] = 2101;
	    }
	    $pameldte = $TWIG['festivalen']->innslag();
	    foreach( $pameldte as $i ) {
		    $innslag = new innslag( $i['b_id'] );
		    if( !in_array( $innslag->get('b_kommune'), $nedslagsfelt ) ) {
			    continue;
			}
			
			$TWIG['mine_innslag'][] = $innslag;
		}
		
   		$TWIG['mediegrupper'] = array('land'=>'UKM-festivalen', 'fylke'=>'Fylkesfestival', 'kommune'=>'Lokalmønstring');

        return $this->render('MinPRBundle:Hjem:oversikt.html.twig', $TWIG);
    }
}
