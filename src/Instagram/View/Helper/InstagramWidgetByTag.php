<?php 

namespace Instagram\View\Helper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class InstagramWidgetByTag extends AbstractHelper implements ServiceLocatorAwareInterface {
	
	protected $serviceLocator;
	 
	/**
	 * Set the service locator.
	 *
	 * @param $serviceLocator ServiceLocatorInterface       	
	 * @return CustomHelper
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}
	/**
	 * Get the service locator.
	 *
	 * @return \Zend\ServiceManager\ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	
    public function __invoke($userId, $content)
    {
        $serviceLocator = $this->getServiceLocator()->getServiceLocator();
        $config = $serviceLocator->get('Config');
        try {
            $photos = array();
            $matches = null;
            
            $re = "/(?:(?<!&)#(\\w+))\\b/"; 
            preg_match_all($re, $content, $matches);
            
            $instagram = new \Instagram\Instagram();
            $record = $this->getServiceLocator()->getServiceLocator()->get('InstagramProfileService')->findByCodeAndUserId('access_token', $userId);
            if($record){
                $instagram->setAccessToken( $record->getValue() );
                
                foreach ($matches[0] as $item){
                    if(!empty($item)){
                        $item = str_replace('#', '', $item);
                        $photos[] = $instagram->getTagMedia($item);
                    }
                }
            }
            
            return $this->view->render('instagram/partial/phototags', array('photos' => $photos));
            
        }catch (\Exception $e){
            echo $e->getMessage();
            return false;
        }
        
        return null;
    }
}