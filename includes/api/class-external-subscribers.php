<?php

/**
 * Class to handle External Subscribers in general
 */
class Subtext_External_Subscribers extends Subtext_API
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_external_subscribers()
    {
        $subscribers = $this->get( '/external_subscribers' );

        return $subscribers;
    }

    public function get_external_subcriber_by_id( $id )
    {
        $subscriber = $this->get( '/external_subscribers/' . $id );

        return $subscriber;
    }
}