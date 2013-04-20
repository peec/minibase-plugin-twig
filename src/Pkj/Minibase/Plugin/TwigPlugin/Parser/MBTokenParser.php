<?php
namespace Pkj\Minibase\Plugin\TwigPlugin\Parser;

use Minibase\Mvc\View;

abstract class MBTokenParser extends \Twig_TokenParser{

	/**
	 * 
	 * @var Minibase\Mvc\View
	 */
	protected $view;
	
	public function __construct (View $view) {
		$this->view = $view;
	}
	
}