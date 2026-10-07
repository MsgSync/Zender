<?php
/**
 * Name: MVC Framework
 * About: KhulnaSoft MVC Framework
 * Copyright: 2020, All Rights Reserved.
 * Author: KhulnaSoft <mail@khulnasoft.com>
 */

/**
 * MVC_Model
 * @package MVC
 * @author KhulnaSoft <mail@khulnasoft.com>
 */

#[\AllowDynamicProperties]
class MVC_Model
{
    /**
     * $db
     * The database object instance
     * @access public
     */
    
    public $db = false;
    
    /**
     * Class constructor
     * @access public
     */
    
    public function __construct($poolname = false)
    {
        $this->db = mvc::instance()->controller->load->database($poolname);
    }
}
