<?php

define('TESTING', true);
$params = implode(' ', array_slice($_SERVER['argv'], 1));
$run = 'php '.dirname(__DIR__).'/run/run.php';
$pm = 'php '.dirname(__DIR__).'/pm/pm.php';
if (isset($_SERVER['argv'][1]) and ($_SERVER['argv'][1] == 'proj' or $_SERVER['argv'][1] == 'plib')) {
  if (empty($_SERVER['argv'][3])) {
    throw new Exception('projectName param #3 not defined. cmd: '.implode(' ',$_SERVER['argv']));
  }
  if ($_SERVER['argv'][1] == 'proj' and $_SERVER['argv'][2] == 'g' and $_SERVER['argv'][3] == 'test') {
    system("$pm localServer createTestProject common", $exitCode);
    if ($exitCode) {
      system("$pm localServer deleteProject test");
      return;
    }
  }
  $offset = $_SERVER['argv'][1] == 'plib' ? -1 : 0;
  if ($_SERVER['argv'][1] == 'plib') $includes = ' '.$_SERVER['argv'][4 + $offset];
  else $includes = ' tst';
  $cmd = "$run site {$_SERVER['argv'][3 + $offset]} \"new CliTestRunner('$params')\"$includes";
  print "$cmd\n";
  print `$cmd`;
}
else {
  $includes = 'tst';
  if (isset($_SERVER['argv'][1]) and $_SERVER['argv'][1] == 'lib') $includes .= ','.$_SERVER['argv'][2];
  print `$run "new CliTestRunner('$params')" $includes`;
}
