<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class Model_Customer extends Model {

    function __construct() {
    	parent::__construct('customer');

    }

    public function validationOnFastUpdate() {

    }

    public function validationOnFastCreate() {
/*
        list($y, $m, $d) = explode("-", $this->birthday);
        if(!checkdate($m, $d, $y)){
            $this->errors['birthday'][] = array('Data di nascita', 'Inserire una data di nascita esatta.');
        }
*/

            $this->validates_presence_for( array(
                    'email', 'password'
                ), array('Email', 'Password'),
                'non pu&ograve; essere vuoto.');


        $this->validates_email_of('email', 'Email', 'deve essere un indirizzo di posta elettronica valido.');
        $this->validates_uniqueness_of('email', 'Email', 'questo indirizzo di posta elettronica &egrave; stato gi&agrave; registrato.');
        $this->password = sha1($this->password);

        parent::validate();
    }
    
    public function validationOnCreate() {
 
        list($y, $m, $d) = explode("-", $this->birthday);
        if(!checkdate($m, $d, $y)){
            $this->errors['birthday'][] = array('Data di nascita', 'Inserire una data di nascita esatta.');
        }
    	$this->validates_presence_for( array(
    	'name', 'surname', 'wherebirth', 'password', 'country'
    	), array('Nome', 'Cognome', 'Luogo di nascita', 'Password', 'Nazionalit&agrave;'),
    	'Non pu&ograve; essere vuoto.');

        parent::validate(); 
    }
    public function validationOnDelete() { }

    public function validationOnUpdate() {
     

    }

}