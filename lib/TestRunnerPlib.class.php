<?php

/**
 * Test runner for: Standalone Libs (project envirnment)
 */
class TestRunnerPlib extends TestRunnerLib {

  function __construct($projectName, $libPath, $filterNames = null) {
    parent::__construct($libPath, $filterNames);
  }

}
