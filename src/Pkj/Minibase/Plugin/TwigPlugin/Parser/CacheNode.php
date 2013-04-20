<?php
namespace Pkj\Minibase\Plugin\TwigPlugin\Parser;

use Minibase\Mvc\View;

class CacheNode extends \Twig_Node{

	/**
	 * Compiles the node to PHP.
	 *
	 * @param Twig_Compiler A Twig_Compiler instance
	 */
	public function compile(\Twig_Compiler $compiler){

		$compiler
		->addDebugInfo($this)
		->write("echo \$context['twigMBViewVar']->cache(".$this->getAttribute('cacheKey').",function () { \n")
		->subcompile($this->getNode('body'))
		->write("}, ".$this->getAttribute('expire').", true);\n")
		;



	}
}