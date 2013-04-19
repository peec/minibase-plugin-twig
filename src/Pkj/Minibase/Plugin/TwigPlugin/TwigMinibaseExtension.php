<?php
namespace Pkj\Minibase\Plugin\TwigPlugin;


class TwigMinibaseExtension extends \Twig_Extension {
	
	public $plugin;
	
	public function __construct(TwigPlugin $plugin) {
		$this->plugin = $plugin;
	}
	
	public function getFilters () {
		$filters = array();
		
		$filters[] = new \Twig_SimpleFilter('route', function ($string) use ($plugin) {
			return $this->plugin->currentView->call($string)->reverse();
		});
		
		return $filters;
	}
	
	
}