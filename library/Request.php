<?php
    /**
     *  @packaeg fuse-update-server
     *
     *  Thusis our base request class.
     */
    
    namespace Fuse\Plugin\UpdateServer;
    
    
    abstract class Request {
        
        /**
         *  @var array The arguments for this request.
         */
        protected $_args;
        
        
        
        
        /**
         *  Object constructor.
         */
        public function __construct ($args = array ()) {
            $this->_args = $args;
        } // __construct ()
        
        
        
        
        /**
         *  Get the data for this request.
         */
        abstract public function call ($args = array ());
        
    } // abstract class Request