<?php

class HelloController extends ApplicationController {

    public function before_filter() {
        $lang = Lang::load('lang');
        //If you want to load another layout:
       //View::$layout = 'anothercontrollername';

    }
    
    public function welcome() {
        # if(!isset($this->queryString[0]))
        #         die("niente");
        #$this->links = Pagination::create('data', 1);
        $this->word = "Hello World!!!";
        $greetings = "ciao";
        Flash::set('general', 'This is a flash message');
        Error::set('general', 'This is an error!!!');
        Error::set('general', 'This is another error!!!');
        Format::json(array('word' => $this->word));
        Format::xml(array('word' => $this->word));
        #Redirect::store(array('site', 'add_user'));
    }
    
    public function redirectTo()
    {
        Flash::set('general', "You did a redirect");
        Redirect::to(array('hello','welcome'));
    }
    # EXAMPLE OF DB USAGE
    public function addAccount() {

    }
    
    public function register() {
    	#check db settings and create a form(inside view/hello/register.php) before
        $this->user = R::dispense('accounts');
        $this->user->username = $this->post->username;
        $this->user->azienda = $this->post->nickname;
        $this->user->email = $this->post->email;
        $this->user->password = sha1('trial');
        $this->user->confirmed_password = sha1('trial');
        try {
        		R::store($this->user);
			    Flash::set('general', "Account creato con successo");
			    Redirect::to(array('hello','register'));
		    } catch(ModelException $e) {
			    #print_r($this->user->viewModelErrors());
		    }	
    }

}