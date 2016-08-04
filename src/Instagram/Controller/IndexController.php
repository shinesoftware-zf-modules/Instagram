<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Instagram\Controller;

use Base\Service\SettingsService;

use Instagram\Entity\Event;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Instagram\Service\InstagramServiceInterface;
use Base\Service\SettingsServiceInterface;
use Base\Model\UrlRewrites as UrlRewrites;

class IndexController extends AbstractActionController
{
	protected $instagramProfile;
	protected $instagramService;
	protected $baseSettings;
	protected $translator;
	
	/**
	 * preDispatch instagram of the instagram
	 * 
	 * (non-PHPdoc)
	 * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
	 */
	public function onDispatch(\Zend\Mvc\MvcEvent $e){
		$this->translator = $e->getApplication()->getServiceManager()->get('translator');
		
		return parent::onDispatch( $e );
	}
	
	/**
	 * Constructor 
	 * @param \Instagram\Service\InstagramProfileService $instagramProfile
	 * @param \Base\Service\SettingsService $settingservice
	 */
	public function __construct(\Instagram\Service\InstagramService $instagramService, \Instagram\Service\InstagramProfileService $instagramProfile, \Base\Service\SettingsService $settingservice)
	{
		$this->instagramService = $instagramService;
		$this->instagramProfile = $instagramProfile;
		$this->baseSettings = $settingservice;
	}
	
	
	/**
	 * Get the list of the active and visible instagram 
	 * 
	 * (non-PHPdoc)
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
    public function indexAction ()
    {
        $vm = new ViewModel(array());
        $vm->setTemplate('instagram/index/form' );
        return $vm;
    }
    
    public function callbackAction ()
    {
        
        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
        
        $record = $this->getServiceLocator()->get('InstagramProfileService')->findByCodeAndUserId('access_token', $userId);
        if(!$record){
            $record = new \Instagram\Entity\InstagramProfiles();
        }
        
        $config = $this->getServiceLocator()->get('config');
        if(!empty($config['InstagramClient'])){
            $auth_config = array(
                    'client_id'         => $config['InstagramClient']['client_id'],
                    'client_secret'     => $config['InstagramClient']['client_secret'],
                    'redirect_uri'      => $config['InstagramClient']['redirect_uri'],
                    'scope'             => $config['InstagramClient']['scope']
            );
            
            $auth = new \Instagram\Auth( $auth_config );
            
            // Save the profile
            $record->setCreatedat(date('Y-m-d H:i:s'));
            $record->setUserId($userId);
            $record->setParameter('access_token');
            $record->setValue($auth->getAccessToken( $_GET['code'] ));
            $this->instagramProfile->save($record);
            
            $this->flashMessenger()->setNamespace('success')->addMessage('Instagram has been set!');
            
            return $this->redirect()->toRoute('profile');
        }else{
            throw new \Exception('You have to config your instagram.local.php file!');
        }
    }
    
    /**
     * get the instagram pages of the user
     */
    public function authAction(){
        
        $config = $this->getServiceLocator()->get('config');
        
        $auth_config = array(
                'client_id'         => $config['InstagramClient']['client_id'],
                'client_secret'     => $config['InstagramClient']['client_secret'],
                'redirect_uri'      => $config['InstagramClient']['redirect_uri'],
                'scope'             => $config['InstagramClient']['scope']
        );
    
        $auth = new \Instagram\Auth( $auth_config );
        $auth->authorize();
    
    }
}
