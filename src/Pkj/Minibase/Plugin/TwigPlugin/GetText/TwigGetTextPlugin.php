<?php
namespace Pkj\Minibase\Plugin\TwigPlugin\GetText;

use Pkj\Minibase\Plugin\TwigPlugin\GetText\Parser\TransToken;


class TwigGetTextPlugin extends \Twig_Extension {
	
	public function getName () {
		return "Twig gettext plugin.";
	}
	
	
	/**
	 * Returns a list of filters to add to the existing list.
	 *
	 * @return array An array of filters
	 */
	public function getFunctions() {
		return array(
				'dgettext' => new \Twig_Function_Function('dgettext'),
				'dngettext' => new \Twig_Function_Function('dngettext'),
				'gettext' => new \Twig_Function_Function('gettext'),
				
				);
	}
}