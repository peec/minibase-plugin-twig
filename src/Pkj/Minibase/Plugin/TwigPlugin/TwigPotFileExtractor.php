<?php
namespace Pkj\Minibase\Plugin\TwigPlugin;

use Minibase\I18n\PotFileGenerator;

class TwigPotFileExtractor extends PotFileGenerator {
	
	/**
	 * @var \Twig_Environment
	 */
	public $twig;
	
	public function setTwig (\Twig_Environment $twig) {
		$this->twig = $twig;
	}
	
	public function getExtensions(){
		return array('twig','html');
	}
	
	public function getFiles (array $files) {
		$tmpfname = tempnam(sys_get_temp_dir(), 'twig_php_cache');
		
		if (!$tmpfname) {
			throw new \Exception ("Cannot reserve temp file for twig php generation.");
		}
		
		$temp = fopen($tmpfname, "w");
		
		$twig = $this->twig;
		$twig->setLoader(new \Twig_Loader_String());
		
		fwrite($temp, '<?php ');
		
		foreach ($files as $file) {
			$stream = $twig->tokenize(file_get_contents($file));
			$nodes = $twig->parse($stream);
			$template = $twig->compile($nodes);
			
			// First line remove.
			$template = substr($template, strpos($template, "\n")+strlen("\n"));			
			$out = "/*\n * File: $file\n */\n";
			$out .= $template;
			
			
			fwrite($temp, $out);
		}
		
		fclose($temp);
		
		
		return array($tmpfname);
	}
	
	public function cleanup (array $files) {
		unlink($files[0]);
	}
	
}