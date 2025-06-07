<?php
if (!defined('ABSPATH'))
    exit;

class Telr_Loader
{

    public function init()
    {
        new Telr_Assets();
        new Telr_Templates();
        new Admin_Page();
        new Telr_Webhook_REST_Controller();





        // Future: new Telr_Webhooks();, new Telr_Admin();
    }
}
