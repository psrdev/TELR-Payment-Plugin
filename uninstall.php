<?php
class Uninstall
{

    private function drop_tables()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';
        $sql = "DROP TABLE $table_name";
        dbDelta($sql);


    }
    private function delete_options()
    {

    }
}