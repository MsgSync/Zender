<?php
/**
 * Name: MVC Framework
 * About: KhulnaSoft MVC Framework
 * Copyright: 2020, All Rights Reserved.
 * Author: KhulnaSoft <mail@khulnasoft.com>
 */

/**
 * MVC_ErrorHandler
 * A simple exception handler to display exceptions in a formatted box
 * @package MVC
 * @author KhulnaSoft <mail@khulnasoft.com>
 */

function MVC_ErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (error_reporting() === 0)
        return;
    
    if (error_reporting() & $errno)
        throw new MVC_ExceptionHandler($errstr, $errno, $errno, $errfile, $errline);
}
