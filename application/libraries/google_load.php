<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
// -------------------------------------------------------------------------------------------------
/**
 * Google Load
 * Google AJAX Libraries api easy loader
 *
 * Google load makes using the Google AJAX Libraries api easy. The AJAX Libraries API is a content distribution 
 * network (CDN) and loading architecture for the most popular, open source JavaScript libraries. 
 * By using the google.load()  method, your application has high speed, globally available access to a 
 * growing list of the most popular, open source JavaScript libraries. (taken from ajax libraries website)
 *
 * Libraries in Google AJAX Libraries:
 *      jquery
 *      jqueryui
 *       prototype
 *       scriptaculous
 *       mootools
 *       dojo
 *       swfobject
 *       yui
 *       ext-core
 *       chrome-frame
 *       webfont
 * 
 * The huge performance advantage of the Google AJAX Libraries is when a website visitors visits another
 * website that is using the Google AJAX Libraries hosting. i.e. John visits another website using Google AJAX Libraries
 * and then he visits your website. The js file from the first site is already cached in his browser and
 * BANG! your website loads even faster. This ultimately will mean that visitors will stay on your website longer
 * since the page loaded that much faster.
 *
 * Google load will react differently depending on whether it is in a production or development environment.
 * In a production environment, google ajax libraries will serve the newest and compressed ajax libraries.
 * In a development environment, the html ouput is much easier to read and the execution time is displayed
 * in a html comment. In the dev enviroment you have the other option to serve the files uncompressed so as to debug.
 *
 * @package		CodeIgniter
 * @subpackage          Libraries
 * @category            Google AJAX Libraries api loader
 * @author		Kyle King <highergroundstudio.com>
 * @version		1.00
 * @license		http://www.opensource.org/licenses/bsd-license.php BSD licensed.
 *
 */

/*
	===============================================================================================
	 USAGE
	===============================================================================================

	Load the library as normal:
	-----------------------------------------------------------------------------------------------
		$this->load->library('google_load');
	-----------------------------------------------------------------------------------------------

	Configuration happens in a config file (included)
	See the included config file for more info.

	-----------------------------------------------------------------------------------------------


	There are 4 options. 1 is required:

	gl_api
	STRING api generated at http://code.google.com/apis/ajaxsearch/signup.html

	n_version
	Array of google ajax libraries newest version of hosted JavaScript libraries

	dev
	BOOLEAN For turning on the development enviroment where the html ouput is much easier
        to read and the execution time is displayed in a html comment
  
        uncompressed
        BOOLEAN For turning on the serving of uncompressed javascript libraries. This is for debugging


	Add libraries like so:
	-----------------------------------------------------------------------------------------------
		// add one library
		$this->google_load->load("jquery");

		// add multiple libaries
		$this->google_load->load("jquery","jqueryui");

                (Make sure there is a comma between and no spaces)
	-----------------------------------------------------------------------------------------------
        List of libraries hosted and the name that you must use to load them
 *              jquery
 *              jqueryui
 *              prototype
 *              scriptaculous
 *              mootools
 *              dojo
 *              swfobject
 *              yui
 *              ext-core
 *              chrome-frame
 *              webfont
	===============================================================================================
*/
class Google_load {

    function __construct($config=array()) {
        // Get CI instance
        $this->ci = & get_instance();
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $this->$k = $v;
            }
        } else {
            show_error("Google Load Config File is Missing or is Empty");
        }
    }

    function load($module_name_string) {
        $time_start = microtime(true);
        $modules = "";
        if ($this->dev) {
            //Serve uncompressed ajax libraries
            $dev  = ($this->uncompressed ? ',{uncompressed:true}' : '');
            //Pull module names and format them
            foreach (explode(",", $module_name_string) as $module_name) {
               foreach ($this->n_version as $module_n => $version) {
                    if ($module_name == $module_n) {
                        $modules .= "\n" . 'google.load("' . $module_name . '","' . $version . '"' . $dev . ');';
                    }
                }
            }
            echo "<!--START google Google AJAX Libraries-->\n<script" . ' type="text/javascript" src="http://www.google.com/jsapi' . ($this->gl_api ? $api_key = "?key=" . $this->gl_api : '') . '"' . "></script>\n" . '<script type="text/javascript">' . $modules . "\n</script>";
            unset($api_key, $modules, $module_name_string, $module_name, $module_n, $version, $dev);
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            echo "\n<!--END google Google AJAX Libraries **Loaded in: " . $time . " seconds-->";
        }
//---------------------------------------------NON DEVELOPMENT---------------------------------------------------//
        else {
            foreach (explode(",", $module_name_string) as $module_name) {
                foreach ($this->n_version as $module_n => $version) {
                    if ($module_name == $module_n) {
                        $modules .= 'google.load("' . $module_name . '","' . $version . '");';
                    }
                }
            }
            echo "<script" . ' type="text/javascript" src="http://www.google.com/jsapi' . ($this->gl_api ? $api_key = "?key=" . $this->gl_api : '') . '"' . "></script>" . '<script type="text/javascript">' . $modules . "</script>";
            unset($api_key, $modules, $module_name_string, $module_name, $module_n, $version);
        }
    }
}
?>