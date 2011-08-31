<?php
/*
Plugin Name: ScholarPress Vitaware
Plugin URI: http://clioweb.org
Description: Gets and displays a CV from Zotero.
Authors: Jeremy Boggs and Sean Takats
Version: 0.3
Author URI: http://clioweb.org
*/

// currently requires user to create a WordPress page with a shortcode of the form
// [scholarpress-vitaware user="<username>"] where <username> is a Zotero username

if ( !class_exists( 'Scholarpress_Vitaware' ) ) :

class Scholarpress_Vitaware {
    
    function scholarpress_vitaware() {
        add_shortcode('scholarpress-vitaware', array($this, 'shortcode'));  
    }
    
    function get_cv($user) {

        // Got to get the user and the user ID, unless this changes on Zotero.org.
        $path = 'http://www.zotero.org/'.$user.'/cv/';

        if($html = file_get_contents($path)) {
        
            $dom = new DOMDocument();
        
            if($dom->loadHTML($html)) {
            
                if($contents = $dom->getElementById('cv')) {                
                    $cv = new DOMDocument();
                    $xmlContent = $cv->importNode($contents,true);
                    $cv->appendChild($xmlContent);
					// remove photo
                    if ($photo = $cv->getElementbyId('profile-picture')) {
  						$photo->parentNode->removeChild($photo);
					}
                    return $cv->saveHTML();                            
                }
            }
        }
    }

    function shortcode($atts) {
    	extract(shortcode_atts(array(
    		'user' => '',
    	), $atts));
    
        $html = '';
        $html .= $this->get_cv($user);
        return $html; 
    }
}

endif;

$scholarpress_vitaware = new Scholarpress_Vitaware();