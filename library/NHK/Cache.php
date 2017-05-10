<?php

class NHK_Cache {

	/**
	 * @var Zend_Cache_Core
	 */
	private $_cache;
	
	
	public function __construct($lifetime = 1800, $subdir='' ){
		$dir=realpath(dirname(__FILE__))."/../../"."public/cache";
		if($subdir != ''){
			$dir = $dir."/".$subdir;
		}

		if (!file_exists($dir)) {
			mkdir ( $dir  , 0775  );
		} 
		$frontendOptions = array ('lifetime' => $lifetime, 'automatic_serialization' => true );
		$backendOptions = array ('cache_dir' => $dir );
		$this->_cache = Zend_Cache::factory ( 'Core', 'File', $frontendOptions, $backendOptions );
	}
	
	
	public function getInstance() {
		return $this->_cache;
	}
	
	public function load($keyName) {
		return $this->_cache->load ( $keyName );
	}
	
       
        
	public function save($keyName, $dataToStore) {
                //$compressorHtml = new D_Minify_HTML();
                //$html = $compressorHtml->compress($dataToStore);
		$cache = Zend_Registry::getInstance()->get('config')->system->cache;
		if(!$cache)
			return;
		
                switch ($keyName){
                    case 'game':
                    case 'news':
                    case 'album':
                    case 'comic':
                    case 'giftcode':
                    case 'new_album_home':
                    case 'new_clip_home':
                    case 'hot_news_home':
                    case 'new_news_home':
                    case 'new_guide_home':
                    case 'list_status_home':
                    case 'homepage':
                    case 'slide_banner_home':
//                        $html = D_MinifyHtml::replace_tabs_newlines($dataToStore, array(
//                                    'cssMinifier' => array('Minify_CSS', 'minify'),
//                                    'jsMinifier' => array('JSMin', 'minify'),'xhtml'=>true));
                        $html = new D_MinifyHtml($dataToStore);
                        break;
                    default:
                        $html = $dataToStore;
                        break;
                }
                /*, array(
                                    'cssMinifier' => array('Minify_CSS', 'minify'),
                                    'jsMinifier' => array('JSMin', 'minify'))*/
		return $this->_cache->save ( $html, $keyName );
	}
	
	public function clean(){
		return $this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
	}
        



}

?>
