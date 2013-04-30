<?php
namespace Pkj\Minibase\Plugin\TwigPlugin;

use Minibase\Plugin\Plugin;
use Minibase\MB;

class TwigPlugin extends Plugin {
	public $twig;
	
	/**
	 * 
	 * @var Pkj\Minibase\Plugin\TwigPlugin\TwigMinibaseExtension
	 */
	public $ext;
	
	/**
	 * 
	 * @var Pkj\Minibase\Mvc\View
	 */
	public $currentView;
	
	// Events
	private $onRender;
	private $twigPotHandler;
	
	
	public function setup() {
		if (!isset($this->mb->cfg[MB::CFG_VIEWPATH])) {
			throw new \Exception ("Twig needs to know where the views are located! MB::CFG_VIEWPATH must be set to the MB instance. Use \$mb->setConfig(MB::CFG_VIEWPATH, 'path/to/views')");
		}
		
		$loader = new \Twig_Loader_Filesystem($this->mb->cfg[MB::CFG_VIEWPATH]);
		// Custom loader config bound to $loader.
		if (isset($this->config['loaderCallback'])) {
			$callback = $this->config['loaderCallback']->bindTo($loader);
			$callback();
		}
		
		$this->mb->events->trigger("plugin:twig:loader", array($loader));
		
		$twig = new \Twig_Environment($loader, $this->config);
		
		$this->mb->events->trigger("plugin:twig:environment", array($twig));
		
		
		$this->twig = $twig;
		
		$this->ext = new TwigMinibaseExtension($this);
		// Add the extension for custom behavior based on minibase functions.
		$twig->addExtension($this->ext);
		$twig->addExtension(new \Twig_Extensions_Extension_Gettext);
		
		// Custom twigCallback bound to $this->twig.
		if (isset($this->config['twigCallback'])) {
			$callback = $this->config['twigCallback']->bindTo($this->twig);
			$callback();
		}
		
		
		
		$plugin = $this;
		
		// Add event to onRender, override it.
		$this->onRender = function (&$extHandlers) use ($plugin) {
			if (!isset($extHandlers['twig'])) {
				$callback = function ($vars, $view, $viewPath) use ($plugin) {
					// Set current view instance.
					$plugin->currentView = $this;
					$vars['twigMBViewVar'] = $this;
					// ignore $viewPath, we add this to Twig_Loader_Filesystem.
					echo $plugin->twig->render($view, $vars);
				};
				$extHandlers['twig']  = $callback;
				$extHandlers['html']  = $callback;
			}
		};

		
		$this->twigPotHandler = function (&$typeMap) use ($plugin) {
			if (!isset($typeMap['twig'])) {
				$typeMap['twig'] = function ($file) {
					if (in_array(strtolower($file->getExtension()), array('twig', 'html'))) {
						
					}
				};
			}
		};
	}
	
	public function start () {
		// Listen to render event.
		$this->mb->events->on("before:render:extension", $this->onRender);
		$this->mb->events->on("mb:generate-po", $this->twigPotHandler);
	}
	
	public function stop () {
		$this->mb->events->off("mb:render", $this->onRender);
		$this->mb->events->off("mb:generate-po", $this->twigPotHandler);
	}
}