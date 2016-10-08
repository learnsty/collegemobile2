<?php

namespace Contracts\Policies;

interface CacheAccessInterface {

    public function set(\string $key, \string $val);

    public function get(\string $key);

}


?>