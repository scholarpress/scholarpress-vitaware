<?php
/*
Plugin Name: ScholarPress Vitaware
Plugin URI: http://clioweb.org
Description: Gets and displays a CV from Zotero.
Authors: Jeremy Boggs and Sean Takats
Version: 0.4
Author URI: http://clioweb.org
*/

// currently requires user to create a WordPress page with a shortcode of the form
// [scholarpress-vitaware user="<userid>"] where <userid> is a Zotero user's id number

if ( !class_exists( 'Scholarpress_Vitaware' ) ) :

class Scholarpress_Vitaware {
    
    function scholarpress_vitaware() {
        add_shortcode('scholarpress-vitaware', array($this, 'shortcode'));  
    }
    
    function get_cv($user) {

        $path = 'http://www.zotero.org/api/users/'.$user.'/cv';

		if($xml = file_get_contents($path)) {
			$dom = new DOMDocument();
            if($dom->loadXML($xml)) {
            
            	if ($sectionsNodeList = $dom->getElementsByTagNameNS('http://zotero.org/ns/api', 'cvsection')) {
    
					$cv = new DOMDocument();

					//only grab CV sections
					foreach ($sectionsNodeList as $domElement){
   						$domNode = $cv->importNode($domElement, true);
   						$cv->appendChild($domNode);
					}

					// transform XML with divs for CSS formatting	
					$xsl = new DOMDocument();
					$xsl->loadXML(file_get_contents(plugin_dir_url( __FILE__ ).'cv.xsl'));					 
					$proc = new XSLTProcessor();
					$proc->importStyleSheet($xsl);
					$cvXML = $proc->transformToXML($cv);
					return $cvXML;
					
            	}

            }
        }
    }

    function shortcode($atts) {
    	extract(shortcode_atts(array(
    		'user' => '',
    	), $atts));


        $html = '';
	    if(!function_exists('apc_cache_info')) {
	        $html .= $this->get_cv($user);
	    }
	    else {
            if (($html = apc_fetch('sp-zotero-cv-'.$user)) === false){        
            	$html .= $this->get_cv($user);
            	// cache CV for one hour
    			apc_store('sp-zotero-cv-'.$user, $html, 3600);
            }
        }

        return $html; 
    }
}

endif;

$scholarpress_vitaware = new Scholarpress_Vitaware();