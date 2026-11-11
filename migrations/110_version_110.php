<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Migration for API Data Builder Sample v1.1.0 (Data Builder v2.5.0 companion upgrade).
 */
class Migration_Version_110 extends App_module_migration
{
    public function up()
    {
        require_once(__DIR__ . '/../install.php');
    }
}

