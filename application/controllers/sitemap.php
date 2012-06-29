<?php
/**
 * Description of sitemap
 *
 * @author Juris <juris.malinens@inbox.lv
 */
class Sitemap extends CI_Controller {
    //put your code here
    
    /**
     * Array of sitemap data
     * @var array 
     */
    public $aData;
    
    
    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('wap4', 'text');
        load_settings();
        $results = $this->news_model->get_news($config['per_page'],$skaits, $this->lang->lang());
        
        /**
         * News
         */
        foreach ($results->result() as $jaunumi):
            $this->aData[] = array(
            "date" => date("c", strtotime($jaunumi->date)),
            "link" => "/".$this->lang->lang()."/news/archive/".$jaunumi->id
            );
        endforeach;
        
        /**
         * navigation
         */
        foreach ($this->data['navigation'] as $nav):

            $this->aData[] = array(
            "date" => date("c", filemtime(APPPATH."controllers/".$nav.".php")),
            "link" => "/".$this->lang->lang()."/".$nav
            );

        endforeach;
        
        /**
         * Manual links
         */

        $this->aData[] = array(
        "date" => date("c", filemtime(APPPATH."controllers/auth.php")),
        "link" => "/".$this->lang->lang()."/auth/create_user"
        );
        $this->aData[] = array(
        "date" => date("c", filemtime(APPPATH."controllers/auth.php")),
        "link" => "/".$this->lang->lang()."/auth/create_user"
        );
        $this->aData[] = array(
        "date" => date("c", filemtime(APPPATH."views/codecs.php")),
        "link" => "/".$this->lang->lang()."/howto/codecs"
        );
        $this->aData[] = array(
        "date" => date("c", filemtime(APPPATH."views/formats.php")),
        "link" => "/".$this->lang->lang()."/howto/formats"
        );
        $this->aData[] = array(
        "date" => date("c", filemtime(APPPATH."controllers/news.php")),
        "link" => "/".$this->lang->lang()."/news"
        );
        $this->aData[] = array(
        "date" => date("c", filemtime(APPPATH."controllers/welcome.php")),
        "link" => "/".$this->lang->lang()
        );
            
        
  }
  
  function __destruct() {
      echo "</urlset>";
  }
    
    function web() {
        
        echo '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach($this->aData as $xml) {
            echo"<url>
                <loc>http://".$this->config->item("web_host").$xml["link"]."</loc>
                <lastmod>".$xml["date"]."</lastmod>
            </url>
            ";
        }
    }
    
    function mobile() {
        
        echo '<?xml version="1.0" encoding="UTF-8" ?>
 <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">';
        
        foreach($this->aData as $xml) {
            echo"<url>
                <loc>http://".$this->config->item("mobile_host").$xml["link"]."</loc>
                <lastmod>".$xml["date"]."</lastmod>
                <mobile:mobile/>
            </url>
            ";
        }
    }
}

?>
