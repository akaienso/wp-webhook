<?php

class WS_API_Handler {

    public static function register_routes() {
        register_rest_route('wp-webhook/v1', '/receive/(?P<id>\d+)', [
            'methods'  => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
            'callback' => [__CLASS__, 'handle_request'],
            'permission_callback' => '__return_true'
        ]);
    }

    public static function handle_request($request) {
        $id = intval($request['id']);
        $post = get_post($id);

        if (!$post || $post->post_type !== 'ws_endpoint') {
            return new WP_REST_Response(['error' => 'Invalid endpoint ID'], 404);
        }

        WS_Request_Logger::log_request($id, $request);

        return new WP_REST_Response([
            'status' => 'received',
            'endpoint_id' => $id,
            'method' => $request->get_method()
        ], 200);
    }
}
