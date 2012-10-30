<?php 
class Model_Accounts extends Model {

    function __construct() {
    	parent::__construct('accounts');

    }
    
 // Both insert and update usage
    public function dispense() {
    #echo 'dispense';
        #$this->validates_uniqueness_of('nickname', 'this nickame exists');
        #$this->validates_presence_of('nickname', 'the name cant be blank');
       # $this->validates_presence_of('email', 'email cant be blank');
        #$this->validates_length_of('nickname', array('maximum' => 3), 'too long');
        #echo '<pre>';
        #print_r($this);
    }
    public function open() {
    #echo 'open';
        #    echo '<pre>';
       # print_r($this);	
	   # $this->validates_uniqueness_of('nickname', 'this nickame exists');
	 
    }

    public function update() {
    	$this->validates_presence_for( array(
    	'nome', 'cognome', 'azienda'
    	), 
    	'Non pu&ograve; essere vuoto');

        $this->validates_length_for(array(
        'nome', 'cognome', 'azienda'
        ), array('maximum' => 40),'questo campo &egrave; troppo lungo');


        
        $this->validates_email_of('email', 'Inserire un indirizzo di posta elettronica valido');
	    $this->validates_uniqueness_of('email', 'Questo indirizzo di posta elettronica &egrave; stato gi&agrave; registrato');  
	    
	    
	          
	    $this->validates_length_of('password', array('minimum' => 6), 'Inserire una password di almeno 6 caratteri');
        $this->validates_confirmed_field('password', 'Le due password non coincidono');
     	$this->password = sha1($this->password);
    	#$this->confirmed_password = sha1($this->password); 
	    parent::update();
    }
    
    public function after_update() {}

}