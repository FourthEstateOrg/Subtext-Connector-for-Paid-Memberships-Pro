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
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_uri . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode( $this->api_key . ':' ),
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode( $response, true );
    }

    public function post( $endpoint, $data = array() )
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->base_uri . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode( $data ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic ' . base64_encode( $this->api_key . ':' ),
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode( $response, true );
    }
}