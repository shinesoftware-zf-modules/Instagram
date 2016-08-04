<?php
/**
* Copyright (c) 2014 Shine Software.
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions
* are met:
*
* * Redistributions of source code must retain the above copyright
* notice, this list of conditions and the following disclaimer.
*
* * Redistributions in binary form must reproduce the above copyright
* notice, this list of conditions and the following disclaimer in
* the documentation and/or other materials provided with the
* distribution.
*
* * Neither the names of the copyright holders nor the names of the
* contributors may be used to endorse or promote products derived
* from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
* FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
* COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
* CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
* LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
* ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* @package Instagram
* @subpackage Service
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License 
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/

namespace Instagram\Service;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Instagram;

class InstagramService implements InstagramServiceInterface, EventManagerAwareInterface
{
	protected $eventManager; 
	protected $instagram; 
	protected $translator;
	
	public function __construct(\Instagram\Instagram $instagram, \Zend\Mvc\I18n\Translator $translator ){
	    $this->instagram = $instagram;
	    $this->translator = $translator;
	}
	
	public function callback($code){
	    var_dump($code);
	}

	/**
	 * get the list of the facebook pages of the user
	 */
	public function getImages(){
	    $photos = array();
	    if(!empty($this->instagram)){
	        $current_user = $this->instagram->getCurrentUser();
            $media = $current_user->getMedia();
            var_dump($media);
            foreach( $media as $photo ) {
//                   $photos[$page->id] = $page->name;
            }
    	}else{
    	    $photos[] = "No instagram photo have been found!";
    	}

    	return $photos;
	}
	
	
	
	/* (non-PHPdoc)
	 * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
	*/
	public function setEventManager (EventManagerInterface $eventManager){
	    $eventManager->addIdentifiers(get_called_class());
	    $this->eventManager = $eventManager;
	}
	
	/* (non-PHPdoc)
	 * @see \Zend\EventManager\ProfileCapableInterface::getEventManager()
	*/
	public function getEventManager (){
	    if (null === $this->eventManager) {
	        $this->setEventManager(new EventManager());
	    }
	
	    return $this->eventManager;
	}
}