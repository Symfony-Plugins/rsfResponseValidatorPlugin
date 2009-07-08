<?php

  include dirname(__FILE__) . '/../bootstrap/unit.php';

  $harness = new lime_harness(new lime_output_color());
  $harness->register(sfFinder::type('file')
                             ->name('*Test.php')
                             ->in(dirname(__FILE__) . '/..'));

  exit($harness->run() ? 0 : 1);