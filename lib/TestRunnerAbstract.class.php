<?php

Lib::addPearAutoloader('PHPUnit');
require_once 'PHPUnit/Autoload.php';
Lib::addPearAutoloader('PHP');
Lib::addPearAutoloader('File');
Lib::addPearAutoloader('Text');

class TestRunnerAbstract {

  protected $filterClasses = [], $filterPrefix;

  /**
   * @var PHPUnit_Framework_TestSuite
   */
  protected $suite;

  function __construct($filterNames = null) {
    R::set('plainText', true);
    $this->suite = new PHPUnit_Framework_TestSuite('one');
    if ($filterNames) {
      if (is_string($filterNames) and $filterNames[strlen($filterNames) - 1] == '%') {
        $this->filterPrefix = ucfirst(rtrim($filterNames, '%'));
        return;
      }
      $filterNames = (array)$filterNames;
      foreach ($filterNames as $v) $this->filterClasses[] = 'Test'.ucfirst($v);
    }
  }

  protected function addTestSuite($class) {
    $rc = new ReflectionClass($class);
    if ($rc->isAbstract()) return;
    $this->suite->addTestSuite($rc);
  }

  function getClasses() {
    $filter = false;
    if (isset($this->filterPrefix)) {
      $filter = function ($class) {
        return Misc::hasPrefix('Test'.$this->filterPrefix, $class);
      };
    }
    elseif ($this->filterClasses) {
      $filter = function ($class) {
        return in_array($class, $this->filterClasses);
      };
    }
    $classes = array_map(function ($v) {
      return $v['class'];
    }, ClassCore::getDescendants('NgnTestCase', 'Test'));
    if (!$this->filterClasses) {
      $classes = array_filter($classes, function($class) {
        return $class::enable();
      });
    }
    if ($filter) $classes = array_filter($classes, $filter);
    return $classes;
  }

  protected function _run(array $classes) {
    output("running tests: ".implode(', ', $classes));
    output("-t=t-");
    foreach ($classes as $class) {
      $this->addTestSuite($class);
    }
    $this->__run();
  }

  protected function printer() {
    return new CliTestPrinter;
  }

  protected function __run() {
    $result = PHPUnit_TextUI_TestRunner::run($this->suite, [
      'stopOnError' => true,
      'printer' => $this->printer()
      //'listeners'   => []
    ]);
    if ($result->failureCount() or $result->errorCount()) exit(1);
  }

  static $folder;

}

TestRunnerAbstract::$folder = __DIR__;