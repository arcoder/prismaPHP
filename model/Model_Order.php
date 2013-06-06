<?php
class Model_Order extends Model {

    private $myerrors = array();


    function __construct() {
    parent::__construct('order');
    }

    public function validationOnCreate() {

    }

    public function validationOnDelete() {

    }

    public function validationOnUpdate() {
    }

    public function getlastEvent() {
        $events = $this->bean->with('ORDER BY created_at DESC LIMIT 1')->ownHistory;
        foreach($events as $one) {
            return $one;
        }
    }
     public function getTotal() {
            $cart = null;
            $carts = $this->bean->with('ORDER BY created_at DESC LIMIT 1')->ownCart;
            foreach($carts as $one) {
                $cart = $one;
            }
            return H::decimal($cart->getTotal() + $this->bean->availableshipping->price);
    }
    public function getCart() {
        $cart = null;
        $carts = $this->bean->with('ORDER BY created_at DESC LIMIT 1')->ownCart;
        foreach($carts as $one) {
            $cart = $one;
        }
        return $cart;
    }
}
