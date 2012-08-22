<?php


/*
  Controller name: Okfn
  Controller description: Okfn controller for reading actions
 */
class JSON_API_Okfn_Controller {

    protected static $sVars = array ( );

    protected static function getParameters( $sModule ) {
        $parameters = array (
            'users' => array (
                'string' => array (
                    'auth' => false
                )
            )
        );
        if ( !isset ( $parameters [ $sModule ] ) )
            throw new Exception ( "Parameters for module not defined." );

        $out = array();
        foreach ( $parameters [ $sModule ] as $sType => $aParameters ) {
            foreach ( $aParameters as $sValName => $sVal ) {
                $out[ $sValName ] = self::getVar( $sValName, $sVal, $sType );
            }
        }
        return $out;
    }

    private static function getVar ( $sValName, $sVal, $sType ) {
        global $json_api;
        $mReturnVal = is_null ( $json_api->query->$sValName ) ? $sVal : $json_api->query->$sValName;
        return self::sanitize ( $mReturnVal, $sType );
    }

    /**
     * Method to sanitize the values given
     * @param mixed $mValue Value to sanitize
     * @param String $sType type of the Value given by parameters array
     * @return mixed sanitized value
     */
    private static function sanitize ( $mValue, $sType ) {
        switch ( $sType ) {
        case "int":
            if ( $mValue !== false )
                $mValue = (int) $mValue;
            break;
        case "boolean":
            $mValue = (boolean) $mValue;
        case "string":
        default:
            switch ( gettype ( $mValue ) ) {
            case 'string':
                $mValue = strip_tags ( $mValue );
                break;
            case 'boolean':
            default:
                break;
            }
            break;
        }
        return $mValue;
    }

    /**
     * Returns a String containing an error message
     * @param String $sModule Modules name
     * @param type $iCode Errorcode
     */
    protected static function error ( $sModule, $iCode ) {
        $oReturn = new stdClass();
        $oReturn->status = "error";
        switch ( $sModule ) {
        case "users":
            switch ( $iCode ) {
            case 1:
                $oReturn->msg = __ ('Unauthorized.');
                break 2;
            }
            default:
                $oReturn->msg = __ ( 'An undefined error occured.' );
        }
        return $oReturn;
    }


    /**
     * Returns an object listing all site members
     */
    public function get_users () {
        $params = $this->getParameters( 'users' );
        $oReturn = new stdClass();
        if ( $params['auth'] !== '27b' ) {
            return $this->error ( 'users', 1 );
        }

        $out->users = array();

        if (bp_has_members( array('per_page'=>0) )) :
		while (bp_members()) :
			bp_the_member();
            $u = new stdClass();
            $u->user_id = bp_get_member_user_id();
			$u->twitter = bp_get_member_profile_data( array('field'=>'Twitter') );
			$u->location = bp_get_member_profile_data( array('field'=>'Location') );
			$u->about = bp_get_member_profile_data( array('field'=>'Description/ About Me)') );
			$u->website = bp_get_member_profile_data( array('field'=>'Website') );
            $u->email = bp_get_member_user_email();
            $u->login = bp_get_member_user_login();
            $u->display_name = bp_get_member_name();
            $u->permalink = bp_get_member_permalink();
            $u->avatar = bp_get_member_avatar();
            $u->last_active = bp_get_member_last_active();
            $u->registered = bp_get_member_registered();

            array_push($out->users, $u);
		endwhile;
		endif;
        return $out;
    }
}
?>
