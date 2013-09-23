<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class Model_Product extends Model {

    function __construct() {
    	parent::__construct('product');

    }
    
      public function validationOnCreate() {
          
      }
      
      public function validationOnDelete() {}
      
      public function validationOnUpdate() {}
      
      
    public function getImagePath() {
        return Config::INDEX_URL.'/public/product_images/'.rawurlencode($this->image);
    }

    public function isAvailable() {
        if($this->qty > 0)
            return true;
        return false;
    }

    public function getUrl() {
        if($this->bean->subcategories_id == null) {
           return Utility::link_to(H::toAscii($this->bean->categories->name).'/'.H::toAscii($this->bean->title).'/'.$this->bean->id);
        }
        return Utility::link_to(H::toAscii($this->bean->categories->name).'/'.H::toAscii($this->bean->subcategories->name).'/'.H::toAscii($this->bean->title).'/'.$this->bean->id);

    }
}