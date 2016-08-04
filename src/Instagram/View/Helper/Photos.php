<?php 

namespace Instagram\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class Photos extends AbstractHelper implements ServiceLocatorAwareInterface {
	
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

    public function __invoke($userId)
    {
        $serviceLocator = $this->getServiceLocator()->getServiceLocator();
        $config = $serviceLocator->get('Config');
        try {
            $record = $this->getServiceLocator()->getServiceLocator()->get('InstagramProfileService')->findByCodeAndUserId('access_token', $userId);
            
            if($record){
                $instagram = new \Instagram\Instagram();
                $instagram->setAccessToken( $record->getValue() );
                $user = $instagram->getCurrentUser();
                return $this->view->render('instagram/partial/photos', array('data' => $user->getData(), 'media' => $user->getMedia()));
            }
        }catch (\Exception $e){
            return false;
        }
        
        return false;
        
    }
}