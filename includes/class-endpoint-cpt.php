<?php

class WS_Endpoint_CPT {
    public static function register() {
        register_post_type('ws_endpoint', [
            'labels' => [
                'name' => 'Webhook Endpoints',
                'singular_name' => 'Webhook Endpoint'
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title'],
            'menu_icon' => 'dashicons-randomize'
        ]);

        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        add_action('save_post_ws_endpoint', [__CLASS__, 'save_meta'], 10, 2);
    }

    public static function add_meta_boxes() {
        add_meta_box('ws_endpoint_url', 'Endpoint URL', [__CLASS__, 'render_meta_box'], 'ws_endpoint', 'side');
        add_meta_box('ws_endpoint_logs', 'Recent Webhook Requests', [__CLASS__, 'render_log_meta_box'], 'ws_endpoint', 'normal', 'default');
    }

    public static function render_meta_box($post) {
        $endpoint_id = $post->ID;
        $endpoint_url = rest_url("wp-webhook/receive/{$endpoint_id}");
        echo "<p><strong>POST/GET to this URL:</strong><br><code>{$endpoint_url}</code></p>";
    }

    public static function save_meta($post_id, $post) {
        // No custom fields needed now
    }

    public static function render_log_meta_box($post) {
        $logs = WS_Request_Logger::get_logs($post->ID);

        if (empty($logs)) {
            echo "<p>No requests logged yet.</p>";
            return;
        }

        echo '<div style="max-height:500px; overflow-y:auto;"><table class="widefat striped">';
        echo '<thead><tr><th>Time</th><th>Method</th><th>Headers</th><th>Body</th></tr></thead><tbody>';

        foreach (array_reverse($logs) as $log) {
            $time = esc_html($log['timestamp']);
            $method = esc_html($log['method']);
            $headers = esc_html(json_encode($log['headers'], JSON_PRETTY_PRINT));
            $body = esc_html($log['body']);

            echo "<tr>";
            echo "<td style='vertical-align:top;'>{$time}</td>";
            echo "<td style='vertical-align:top;'>{$method}</td>";
            echo "<td style='vertical-align:top;'><pre style='white-space:pre-wrap;'>" . $headers . "</pre></td>";
            echo "<td style='vertical-align:top;'><pre style='white-space:pre-wrap; max-width: 400px; word-wrap: break-word;'>" . $body . "</pre></td>";
            echo "</tr>";
        }

        echo "</tbody></table></div>";
    }
}
