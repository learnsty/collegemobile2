<?php

namespace Contracts\Policies;

interface DBAccessInterface {

      public function get(array $colums);

      public function set(array $columns, array $adds);

      public function let(array $columns, array $adds);

      public function del(array $colums);

}


?>