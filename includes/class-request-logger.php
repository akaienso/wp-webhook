<?php

class WS_Request_Logger {

    public static function log_request($endpoint_id, $request) {
        $log = [
            'timestamp' => current_time('mysql'),
            'method'    => $request->get_method(),
            'headers'   => $request->get_headers(),
            'body'      => $request->get_body(),
        ];

        $existing_logs = get_post_meta($endpoint_id, '_ws_request_logs', true);
        if (!is_array($existing_logs)) $existing_logs = [];

        $existing_logs[] = $log;

        // Limit to 100 logs
        if (count($existing_logs) > 100) {
            array_shift($existing_logs);
        }

        update_post_meta($endpoint_id, '_ws_request_logs', $existing_logs);
    }

    public static function get_logs($endpoint_id) {
        return get_post_meta($endpoint_id, '_ws_request_logs', true);
    }
}
