<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UKMNorge\Arrangement\Filter;
use UKMNorge\Avis\Avis;
use UKMNorge\Nettverk\Omrade;
use UKMNorge\Arrangement\Load as ArrangementLoad;

class HjemController extends Controller
{
    public function oversiktAction($id)
    {
        require_once('UKM/Autoloader.php');

        $TWIG = array();

        $avis = new Avis($id);
        $nedslagsfelt = $avis->getNedslagsfeltAsCSV();

        $sesong = intval(date('Y'));

        /* INFO OM FYLKET */
        $fylke = $avis->getFylke();

        $omrade = Omrade::getByFylke($fylke->getId());

        foreach ($omrade->getAktuelleArrangementer()->getAll() as $arrangement) {
            $i_mitt_nedsalgsfelt = array();
            foreach ($arrangement->getInnslag()->getAll() as $innslag) {
                if ($innslag != $nedslagsfelt) {
                    continue;
                } else {
                    $i_mitt_nedsalgsfelt[] = $innslag;
                }
            }
            $arrangement->setAttr('innslag_som_skal_vises', $i_mitt_nedsalgsfelt);
        }

        $filter = new Filter();
        $filter->sesong($sesong);
        $arr = ArrangementLoad::byEier($fylke, $filter);

        $TWIG = [
            'avis' => $avis,
            'fylke' => $fylke,
            'omrade' => $omrade,
            'sesong' => $sesong,
            'arrangementer' => $arr
        ];

        //Marius kommer tilbake å fikser dette her 
        if (false) {
            $TWIG['vis_festival'] = true;

            $festivalen = new \landsmonstring($sesong);
            $festivalpl = $festivalen->monstring_get();
            $TWIG['land']['monstring'] = new monstring_v2($festivalpl->get('pl_id'));

            $pameldte = $TWIG['land']['monstring']->getInnslag()->getAll();
            foreach ($pameldte as $innslag) {
                if (!in_array($innslag->getKommune()->getId(), $nedslagsfelt)) {
                    continue;
                }
                $TWIG['land']['mine_innslag'][] = $innslag;
            }
            // Marius fikser getPressemelding();
            $TWIG['land']['pressemelding'] = $arrangement->getPressemelding();

            $TWIG['land']['mediegrupper'] = array('land' => 'UKM-festivalen', 'fylke' => 'Fylkesfestival', 'kommune' => 'Lokalmønstring');
        } else {
            $TWIG['vis_festival'] = false;
        }

        return $this->render('MinPRBundle:Hjem:oversikt.html.twig', $TWIG);
    }
}
