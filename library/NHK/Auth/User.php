<?php
/*------------------------------------------
* @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
* @PHONE: +84933731173
* -----------------------------------------*/


/**
 * @category   Zend
 * @package    Zend_Auth
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class NHK_Auth_User
{
    /**
     * Singleton instance
     *
     * @var Zend_Auth
     */
    protected static $_instance = null;

    /**
     * Persistent storage handler
     *
     * @var Zend_Auth_Storage_Interface
     */
    protected $_storage = null;

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * Returns an instance of Zend_Auth
     *
     * Singleton pattern implementation
     *
     * @return Zend_Auth Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Returns the persistent storage handler
     *
     * Session storage is used by default unless a different storage adapter has been set.
     *
     * @return Zend_Auth_Storage_Interface
     */
    public function getStorage()
    {
        if (null === $this->_storage) {
            /**
             * @see Zend_Auth_Storage_Session
             */
            require_once 'Zend/Auth/Storage/Session.php';
            $this->setStorage(new Zend_Auth_Storage_Session('user'));
            
        }

        return $this->_storage;
    }

    /**
     * Sets the persistent storage handler
     *
     * @param  Zend_Auth_Storage_Interface $storage
     * @return Zend_Auth Provides a fluent interface
     */
    public function setStorage(Zend_Auth_Storage_Interface $storage)
    {
        $this->_storage = $storage;
        return $this;
    }

    /**
     * Authenticates against the supplied adapter
     *
     * @param  Zend_Auth_Adapter_Interface $adapter
     * @return Zend_Auth_Result
     */
    public function authenticate(Zend_Auth_Adapter_Interface $adapter)
    {
        $result = $adapter->authenticate();

        /**
         * ZF-7546 - prevent multiple succesive calls from storing inconsistent results
         * Ensure storage has clean state
         */
        if ($this->hasIdentity()) {
            $this->clearIdentity();
        }

        if ($result->isValid()) {
            $this->getStorage()->write($result->getIdentity());
        }

        return $result;
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return boolean
     */
    public function hasIdentity()
    {
        return !$this->getStorage()->isEmpty();
    }

    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        $storage = $this->getStorage();

        if ($storage->isEmpty()) {
            return null;
        }

        return $storage->read();
    }

    /**
     * Clears the identity from persistent storage
     *
     * @return void
     */
    public function clearIdentity()
    {
        $this->getStorage()->clear();
    }
}
