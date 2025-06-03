<?php
if (!defined('ABSPATH'))
    exit;
class Uninstall
{
    public function __construct()
    {
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            exit;
        }

        $this->drop_tables();
        $this->delete_options();
    }

    private function drop_tables()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';
        $sql = "DROP TABLE $table_name";
        dbDelta($sql);


    }
    private function delete_options()
    {
        delete_option('telr_store_id');
        delete_option('telr_auth_key');
        delete_option('telr_webhook');
        delete_option('telr_db_version');


    }
}
