<?php

/**
 * Test runner for: Projects Environment Tests
 */
class TestRunnerProject extends TestRunnerAbstract {

  protected $projectName;

  function __construct($projectName, $filterNames = null) {
    $this->projectName = $projectName;
    parent::__construct($filterNames);
  }

  protected function printer() {
    return new ProjectTestPrinter($this->projectName);
  }

  function getClasses() {
    return array_filter(parent::getClasses(), function ($class) {
      $r = ClassCore::hasAncestor($class, 'ProjectTestCase');
      return $r;
    });
  }

  function _g() {
    return array_filter($this->getClasses(), function ($class) {
      return !strstr(Lib::getClassPath($class), "projects/$this->projectName/");
    });
  }

  /**
   * Запускает проектные тесты, не находящиеся в папке проекта
   */
  function g() {
    $this->_run($this->_g());
  }

  /**
   * Запускает проектные тесты, находящиеся в папке проекта
   */
  function l() {
    $classes = array_filter($this->getClasses(), function ($class) {
      return strstr(Lib::getClassPath($class), "projects/$this->projectName/") or !empty($class::$local);
    });
    $this->_run($classes);
  }

  /**
   * Запускает все проектные тесты
   */
  function a() {
    $this->_run($this->getClasses());
  }

}