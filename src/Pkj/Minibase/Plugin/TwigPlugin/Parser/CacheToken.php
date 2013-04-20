<?php
namespace Pkj\Minibase\Plugin\TwigPlugin\Parser;


class CacheToken extends MBTokenParser {

	/**
	 * Parses a token and returns a node.
	 *
	 * @param Twig_Token $token A Twig_Token instance
	 *
	 * @return Twig_NodeInterface A Twig_NodeInterface instance
	 */
	public function parse(\Twig_Token $token){
		$lineno = $token->getLine();
		
		$cacheKey = $this->parser->getStream()->expect(\Twig_Token::STRING_TYPE)->getValue();
		
		$value = 0;
		if (!$this->parser->getStream()->test(\Twig_Token::BLOCK_END_TYPE)) {
			
			$value = $this->parser->getStream()->expect(\Twig_Token::NUMBER_TYPE)->getValue();
			
		}
		
		$this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
		
		$body = $this->parser->subparse(array($this, 'decideEndOfTag'), true);
		
		$this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
		return new CacheNode(array('body' => $body), 
				array(
					'cacheKey' => $cacheKey,
					'expire' => $value
				), 
				$lineno, 
				$this->getTag()
				);
	}

	public function decideEndOfTag (\Twig_Token $token) {
		return $token->test(array('endcache'));
	}

	/**
	 * Gets the tag name associated with this token parser.
	 *
	 * @return string The tag name
	 */
	public function getTag () {
		return 'cache';
	}

}