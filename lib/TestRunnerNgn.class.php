<?php

/**
 * Test runner for: Framework Core Tests
 */
class TestRunnerNgn extends TestRunnerAbstract {


  function _getClasses() {
    return array_filter(parent::getClasses(), function ($v) {
      return !is_subclass_of($v, 'ProjectTestCase');
    });
  }

  /**
   * Running all core tests
   */
  function run() {
    $this->_run($this->getClasses());
  }

}