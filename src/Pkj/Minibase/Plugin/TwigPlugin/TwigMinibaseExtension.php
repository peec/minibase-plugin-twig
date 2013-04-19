<?php
namespace Pkj\Minibase\Plugin\TwigPlugin;


class TwigMinibaseExtension extends \Twig_Extension {
	
	public $plugin;
	
	public function __construct(TwigPlugin $plugin) {
		$this->plugin = $plugin;
	}
	
	public function getFilters () {
		$filters = array();
		
		$filters[] = new \Twig_SimpleFunction('route', function ($string) use ($plugin) {
			$args = array_slice(func_get_args(), 1);
			return $this->plugin->currentView->call($string)->reverse($args);
		});
		
		return $filters;
	}
	
	
}