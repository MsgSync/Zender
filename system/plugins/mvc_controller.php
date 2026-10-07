<?php
/**
 * Name: MVC Framework
 * About: KhulnaSoft MVC Framework
 * Copyright: 2020, All Rights Reserved.
 * Author: KhulnaSoft <mail@khulnasoft.com>
 */


/**
 * MVC_Controller
 * @package	MVC
 * @author KhulnaSoft <mail@khulnasoft.com>
 */

class MVC_Controller
{

    /**
     * Class constructor
     * @access public
     */

    public function __construct()
    {
        mvc::instance($this, "controller");
        $this->load = new MVC_Load;
    }

    /**
     * __call
     * Gets called when an unspecified method is used
     * @access public
     */
    
    public function __call($function, $args)
    {
        return $this->index();
    }
}
