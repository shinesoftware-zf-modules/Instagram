<?php 

namespace Instagram\View\Helper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class InstagramWidget extends AbstractHelper implements ServiceLocatorAwareInterface {
	
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
            
            $re = "/(https?:\\/\\/.*?(?<=instagram.com\\/)(\\w+))/"; 
            preg_match_all($re, $content, $matches, PREG_SET_ORDER);
            if(!empty($matches[0][2])){
                $record = $this->getServiceLocator()->getServiceLocator()->get('InstagramProfileService')->findByCodeAndUserId('access_token', $userId);
                if($record){
                    $instagram = new \Instagram\Instagram();
                    $instagram->setAccessToken( $record->getValue() );
                    $user = $instagram->getUserByUsername($matches[0][2]);
                    return $this->view->render('instagram/partial/photos', array('data' => $user->getData(), 'media' => $user->getMedia()));
                }
            }
        }catch (\Exception $e){
            return false;
        }
        
        return null;
    }
}