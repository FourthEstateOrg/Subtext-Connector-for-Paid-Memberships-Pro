<?php

abstract class Subtext_API
{
    private $base_uri = 'https://joinsubtext.com/v2';
    private $api_key = null;
    private $campaign_id = null;

    public function __construct()
    {
        $settings = fespmp_get_options();

        $this->api_key = $settings['subtext_api_key'];
        $this->campaign_id = $settings['subtext_campaign_id'];
    }

    public function create_external_subscriber( $data )
    {
        $data['subtext_campaign_id'] = $this->campaign_id;
        $subscriber = $this->post( '/external_subscribers', $data );

        return $subscriber;
    }

    public function get( $endpoint )
    {
        $args = array(
            'headers' => $this->get_headers(),
        );
        $response = wp_remote_get( $this->base_uri . $endpoint, $args );
        $body     = wp_remote_retrieve_body( $response );
        return json_decode( $body, true );
    }

    public function post( $endpoint, $data = array() )
    {
        $args = array(
            'body'    => $data,
            'headers' => $this->get_headers(),
        );
        $response = wp_remote_post( $this->base_uri . $endpoint, $args );
        $body     = wp_remote_retrieve_body( $response );
        return json_decode( $body, true );
    }

    public function get_headers()
    {
        return array(
            'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' )
        );
    }
}
