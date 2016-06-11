<?php
namespace UKMNorge\MinPressemeldingBundle\Services;
use wp_option;
use Exception;

require_once('UKMconfig.inc.php');
require_once('UKM/wp_option.class.php');


class WordpressOptionService
{
	var $pl_id = false;
	var $path = false;
	
	public function __construct() {
	}
	
	public function setMonstring( $pl_id, $path ) {
		$this->pl_id = $pl_id;
		$this->path = $path;
	}
	
	public function getOption( $key ) {
		if( false === $this->pl_id || false === $this->path ) {
			throw new Exception('WordpressOptionService: getOption krever at setMonstring er kjørt først');
		}
		wp_option::setMonstring( $this->pl_id, $this->path );
		return wp_option::getOption( $key );
	}
}