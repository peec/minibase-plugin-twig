<?php
namespace Pkj\Minibase\Plugin\TwigPlugin;


use Pkj\Minibase\Plugin\TwigPlugin\Parser\CacheToken;

class TwigMinibaseExtension extends \Twig_Extension {
	
	public $plugin;
	
	public function getName () {
		return "Minibase Twig Base Functionality.";
	}
	
	public function __construct(TwigPlugin $plugin) {
		$this->plugin = $plugin;
	}
	public function getTokenParsers() {
		$parsers = array();
		
		$parsers[] = new CacheToken($this->plugin->currentView);
		
		return $parsers;
	}
	public function getFilters() {
		$plugin = $this->plugin;
		$funcs = array();
		
		$funcs[] = new \Twig_SimpleFilter('route', function ($string) use ($plugin) {
			$args = array_slice(func_get_args(), 1);
			
			return $plugin->currentView->call($string)->reverse($args);
		});
		
		$funcs[] = new \Twig_SimpleFilter('asset', function ($string) use ($plugin) {
			$args = array_slice(func_get_args(), 1);
			
			return $plugin->currentView->asset($string);
		});
		
		
		return $funcs;
	}
	
	
}