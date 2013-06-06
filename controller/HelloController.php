<?php

class HelloController extends ApplicationController {

    public function before_filter() {
        //View::$layout = 'anothercontrollername';
    }

    public function viewAll($id, $title) {
        $this->id = $id;
        $this->title = $title;
    }

    public function csrf() {
        
    }

    public function welcome() {

        $this->word = "Hello, World!";
        $greetings = "ciao";

        JSON::load(array('word' => $this->word));

    }

    public function redirectTo() {
        Success::set('general', "You did a redirect");
        Redirect::to('hello/welcome', array('ciao' => '1'));
    }

    # EXAMPLE OF DB USAGE

    public function addAccount() {
        
    }

    public function register() {
        #check db settings and create a form(inside view/hello/register.php) before
        $this->user = R::dispense('customer');
        if(isset($_POST['register'])) {   	
        	$this->user->email = $_POST['email'];
        	$this->user->password = $_POST['password'];
        	$this->user->confirmed_password = sha1('demo');
        	try {
        		$this->user->validationOnFastCreate();
            	R::store($this->user);
            	Success::set('general', "Account created");
            	Redirect::to(array('hello', 'register'));
        	} catch (ModelException $e) {
            #print_r($this->user->viewModelErrors());
        	}
        }
    }

}