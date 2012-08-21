<?php

/*
  Controller name: OkfnRead
  Controller description: Okfn controller for reading actions
 */

require_once JSON_API_OKFN_HOME . '/library/functions.class.php';

class JSON_API_OkfnRead_Controller {

        /**
         * Returns an object listing all site members
         * @return Object Profile Fields
         */
        public function get_users () {
                /* Possible parameters:
                 * String username: the username you want information from (required)
                 */
                $this->initVars ( 'users' );
                $oReturn = new stdClass();
                if ( $this->auth !== '27b' ) {
                        return $this->error ( 'users', 1 );
                }

                $oReturn->users = array();
                foreach (  get_users( array( 'blog_id'=>'' ) ) as $u ) {
                    if ($u->spam) {
                        continue;
                    }
                    if ( !bp_has_profile ( array ( 'user_id' => $u->ID ) ) ) {
                        continue;
                    }
                    $user = new stdClass();
                    $user->display_name = $u->display_name;
                    $user->login = $u->user_login;
                    $user->status = $u->user_status;
                    $user->email = $u->user_email;
                    $user->registered = $u->user_registered;
                    $user->twitter = bp_get_profile_field_data( array('user_id'=>$u->ID, 'field'=>'Twitter') );
                    $user->location = bp_get_profile_field_data( array('user_id'=>$u->ID, 'field'=>'Location') );
                    $user->about = bp_get_profile_field_data( array('user_id'=>$u->ID, 'field'=>'Description/ About Me') );
                    $user->website = bp_get_profile_field_data( array('user_id'=>$u->ID, 'field'=>'Website') );
                    array_push( $oReturn->users, $user );
                }
                return $oReturn;
        }

        /**
         * Method to handle calls for the library
         * @param String $sName name of the static method to call
         * @param Array $aArguments arguments for the method
         * @return return value of static library function, otherwise null
         */
        public function __call ( $sName, $aArguments ) {
                if ( class_exists ( "JSON_API_OKFN_FUNCTION" ) &&
                        method_exists ( JSON_API_OKFN_FUNCTION, $sName ) &&
                        is_callable ( "JSON_API_OKFN_FUNCTION::" . $sName ) ) {
                        try {
                                return call_user_func_array ( "JSON_API_OKFN_FUNCTION::" . $sName, $aArguments );
                        } catch ( Exception $e ) {
                                $oReturn = new stdClass();
                                $oReturn->status = "error";
                                $oReturn->msg = $e->getMessage ();
                                die ( json_encode ( $oReturn ) );
                        }
                }
                else
                        return NULL;
        }

        /**
         * Method to handle calls for parameters
         * @param String $sName Name of the variable
         * @return mixed value of the variable, otherwise null
         */
        public function __get ( $sName ) {
                return isset ( JSON_API_OKFN_FUNCTION::$sVars[ $sName ] ) ? JSON_API_OKFN_FUNCTION::$sVars[ $sName ] : NULL;
        }
}

?>
