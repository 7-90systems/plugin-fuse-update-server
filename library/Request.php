<?php
    /**
     *  @packaeg fuse-update-server
     *
     *  Thusis our base request class.
     */
    
    namespace Fuse\Plugin\UpdateServer;
    
    
    abstract class Request {
        
        /**
         *  Object constructor.
         */
        public function __construct () {
            
        } // __construct ()
        
        
        
        
        /**
         *  Get the data for this request.
         */
        abstract public function call ();
        
    } // abstract class Request