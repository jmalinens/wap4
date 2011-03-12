<?php
/**
 * @author Juris Malinens <juris.malinens@inbox.lv> and of course Derek Allard
 * source from http://www.derekallard.com/blog/post/building-an-rss-feed-in-code-igniter/
 */
error_reporting(E_ALL);
ini_set("display_errors","on");
class Feed extends CI_Controller 
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('xml');
    }
    
    function index()
    {
        redirect("feed/rss");
    }
    function rss() {
        $data['encoding']         = 'utf-8';
        $data['feed_name']        = lang('feed.name');
        $data['feed_url']         = 'http://wap4.org/'.$this->lang->lang();
        $data['page_description'] = lang('feed.descr');
        $data['page_language']    = lang('feed.lang');
        $data['creator_email']    = lang('feed.admin');
        $data['posts'] = $this->news_model->getRecentPosts();    
        header("Content-Type: application/rss+xml");
        $this->load->view('feed/rss', $data);        
    }
}
?>
