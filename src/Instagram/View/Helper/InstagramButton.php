<?php 

namespace Instagram\View\Helper;
use Zend\View\Helper\AbstractHelper;

class InstagramButton extends AbstractHelper {
	
    public function __invoke($content)
    {
        $re = "/(https?:\\/\\/.+?(?<=instagram.com\\/)(\\w+))/"; 
        preg_match_all($re, $content, $matches, PREG_SET_ORDER);
        
        if(!empty($matches[0][2])){
            foreach ($matches as $match){
                $instagram['link'] = $match[0];
                $instagram['user'] = $match[2];
                 
                $button = $this->view->render('instagram/partial/buttons', array('instagram' => $instagram));
                $content = str_replace($instagram['link'], trim($button), $content);
            }
        }
       return $content;
       
    }
}