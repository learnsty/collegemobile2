<?php

namespace Contracts\Policies;

interface SessionAccessInterface {

     public function open();

     public function close();

     public function destroy(\string $key);    

     public function read(\string $key);

     public function write(\string $key, \string $val);


}

?>