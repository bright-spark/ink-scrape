<?php

error_reporting(E_ALL | E_STRICT);

include("ink-scrape.php");

//Test #1: test ability to locate text (not necessarily in any order).
$string = " a aga sdg a sgfoo adgamglh ari hahs svbars jgaje jryr eryegaz";
$pos = 0;
$inst =& new InkScrape(0, array(
'foo',
'bar',
'gaz'), $string);
$inst->checkTextFormat();
echo("passed test\n");

//Test #2: test ability to locate text, in specified order.
// the third string comes before the second string, so this case should fail if position is stored
$threw = false;
try {
  $inst =& new InkScrape(0, array(
  'foo',
  'gaz',
  'bar'), $string);
  $inst->checkTextFormat();
} catch (Exception $e) {
  if($e->getMessage()=="unrecognized page format") {
    $threw = true;
  }
}

echo(($threw ? 'passed' : 'failed')." test\n");

?>