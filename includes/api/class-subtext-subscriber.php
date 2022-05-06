<?php

class Subtext_Subscriber extends Subtext_API
{
    /**
     * Undocumented variable
     *
     * @var \WC_User
     */
    public $user;

    public $subtext_uid;

    public $subtext_data;

    /**
     * Undocumented function
     *
     * @param \WP_User $user
     */
    public function __construct( $user )
    {
        parent::__construct();

        $this->user = $user;
        $this->subtext_uid = get_user_meta( $this->user->ID, 'subtext_uuid', true );
    }

    public function unsubscribe()
    {
        $subscriber = $this->post( '/external_subscribers/' . $this->subtext_uid . '/unsubscribe'  );

        return $subscriber;
    }

    public function resubscribe()
    {
        $subscriber = $this->post( '/external_subscribers/' . $this->subtext_uid . '/resubscribe'  );

        return $subscriber;
    }

    public function get_subtext_data()
    {
        $subscriber = $this->get( '/external_subscribers/' . $this->subtext_uid );

        if ( ! isset( $subscriber['external_subscriber'] ) ) {
            return false;
        }

        return $subscriber['external_subscriber'];
    }
}
