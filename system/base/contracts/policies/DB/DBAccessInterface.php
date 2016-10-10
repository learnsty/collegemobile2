<?php

namespace Contracts\Policies;

interface DBAccessInterface {

      public function get(\array $columns, \array $clauseProps, $conjunction);

      public function set(\array $values);

      public function let(\array $columns, $conjunction);

      public function del(\array $columns);

}


?>