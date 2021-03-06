<?php

/**
 * Test runner for: Standalone Libs
 */
class TestRunnerLib extends TestRunnerNgn {

  protected $libPath;

  function __construct($libPath, $filterNames = null) {
    $libPath = static::replace($libPath);
    if (Misc::hasSuffix('.php', $libPath)) $libPath = dirname($libPath);
    $this->libPath = $libPath;
    parent::__construct($filterNames);
  }

  static function replace($path) {
    foreach (['NGN_PATH', 'NGN_ENV_PATH'] as $v) {
      $path = str_replace($v, constant($v), $path);
    }
    return $path;
  }

  function getClasses() {
    return array_filter(parent::getClasses(), function($class) {
      return strstr(Lib::getClassPath($class), $this->libPath);
    });
  }

  function help() {
    return array_values(array_map(function($class) {
      return ClassCore::classToName('Test', $class);
    },$this->getClasses()));
  }

  /**
   * Запускает все тесты указанной библиотеки
   */
  function run() {
    $this->_run($this->getClasses());
  }

}