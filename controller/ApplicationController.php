<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class ApplicationController extends Controller { 
    
    public function before_filter() {

    }

    protected function checkLogin() {
        if(!isset($_SESSION['customer_id'])) {
            Warning::set('login', 'Accedere per continuare.');
            Redirect::to('account/login');
        }
    }
    
}
