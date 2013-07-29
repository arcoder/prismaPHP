<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class ApplicationController extends Controller
{

    public function before_filter()
    {
        session_start();
    }

    protected function checkLogin()
    {
        if (!isset($_SESSION['customer_id'])) {
            Warning::set('login', 'Accedere per continuare.');
            Redirect::to('account/login');
        }
    }

}
