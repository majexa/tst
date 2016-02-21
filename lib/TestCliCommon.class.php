<?php

/**
 * Useful commands for working with tests
 */
class TestCliCommon {

  /**
   * Удаляет ошибки
   */
  function clear() {
    chdir(NGN_ENV_PATH.'/run');
    Cli::shell('php run.php "(new AllErrors)->clear()"');
  }

  /**
   * Shows all available tests
   */
  function lst() {
    $columns = [[],[],[],[]];
    $columns[0][] = 'tst proj g {name}:';
    foreach ((new TestRunnerProject('dummy'))->_g() as $class) {
      $columns[0][] = ClassCore::classToName('Test', $class);
    }
    $columns[1][] ='tst ngn run:';
    foreach ((new TestRunnerNgn)->_getClasses() as $class) {
      $columns[1][] = ClassCore::classToName('Test', $class);
    }
    foreach ($this->getTestLibs() as $name) {
      $columns[2][] = "tst lib $name";
    }
    print Cli::columns($columns, true);
  }

  /**
   * Возвращает имена корневых библиотек имеющих тесты
   */
  protected function _getTestLibs() {
    $r = [];
    foreach (glob(NGN_ENV_PATH.'/*') as $f) {
      $name = basename($f);
      if ($name == 'run') continue;
      if (Dir::getFilesR("$f/lib", 'Test*')) {
        $r[] = $name;
      }
    }
    return $r;
  }

  protected function getTestLibs() {
    return array_filter($this->_getTestLibs(), function($name) {
      return !file_exists(NGN_ENV_PATH."/$name/projectLib");
    });
  }

  /**
   * Возвращает имена корневых библиотек имеющих тесты
   */
  protected function getTestPlibs() {
    return array_filter($this->_getTestLibs(), function($name) {
      return file_exists(NGN_ENV_PATH."/$name/projectLib");
    });
  }

  /**
   * Создаёт проект "test" и запускает для него глобальные проектные тесты
   *
   * @param string|null $filterNames
   */
  function g($filterNames = null) {
    $pm = 'php '.NGN_ENV_PATH.'/pm/pm.php';
    $tst = 'php '.NGN_ENV_PATH.'/tst/tst.php';
    print `$pm localServer createTestProject`;
    $filterNames = $filterNames ? ' '.$filterNames : '';
    print `$tst proj g test$filterNames`;
  }

}