<?php

class TestAllErrors extends NgnTestCase {

  function test() {
    $caption = '';
    foreach ((new AllErrors)->get() as $v) {
      $caption .= $v['body']."\n".$v['trace']. //
        (isset($v['params']['entryCmd']) ? "Entry cmd: ".$v['params']['entryCmd'] : '')."\n=======\n";
    }
    $this->assertTrue($caption === '', $caption);
  }

}