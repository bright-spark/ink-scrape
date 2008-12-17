<?php

error_reporting(E_ALL | E_STRICT);

include("ink-scrape.php");

//Test #1: test ability to locate text (not necessarily in any order).
$string = " a aga sdg a sgfoo adgamglh ari hahs svbars jgaje jryr eryegaz";
$pos = 0;
InkScrape::checkTextFormat(0, array(
'foo',
'bar',
'gaz'), $string);
echo("Test #1: passed test\n");

//Test #2: test ability to locate text, in specified order.
// the third string comes before the second string, so this case should fail if position is stored
$threw = false;
try {
  InkScrape::checkTextFormat(0, array(
  'foo',
  'gaz',
  'bar'), $string);
} catch (UnmatchedBoundaryException $e) {
  $threw = true;
}

echo("Test #2: ".($threw ? 'passed' : 'failed')." test\n");

//Test #3: test ability to locate text, in specified order, with no overlapping of boundaries.
$threw = false;
try {
  $string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tortor quam, pellentesque at, blandit id, consequat malesuada, lectus. Etiam laoreet quam vel metus. Suspendisse eu risus id justo tincidunt auctor.";
  $pos = 0;
  InkScrape::checkTextFormat(0, array(
  'Lorem',
  'Suspendisse',
  'Suspend'), $string);
} catch (UnmatchedBoundaryException $e) {
  $threw = true;
}

echo("Test #2: ".($threw ? 'passed' : 'failed')." test\n");

?>