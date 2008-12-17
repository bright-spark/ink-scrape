<?php

error_reporting(E_ALL | E_STRICT);

include("ink-scrape.php");

$string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tortor quam, pellentesque at, blandit id, consequat malesuada, lectus. Etiam laoreet quam vel metus. Suspendisse eu risus id justo tincidunt auctor.";

/**
 * Test locate-text-general
 * - test ability to locate text (not necessarily in any order)
 */
$pos = 0;
InkScrape::checkFrontBoundariesForText(array(
'Lorem',
'Vestibulum',
'Etiam'), $string);
echo("passed test (locate-text-general)\n");

/**
 * Test locate-text-forward
 * - test ability to locate text, in specified order
 * - the third string comes before the second string, so this case should fail if position isn't stored
 */
$threw = false;
try {
  InkScrape::checkFrontBoundariesForText(array(
  'Lorem',
  'Etiam',
  'Vestibulum'), $string);
} catch (UnmatchedBoundaryException $e) {
  $threw = true;
}

echo((($threw && ($e->matchCount() == 2)) ? 'passed' : 'failed')." test (locate-text-forward)\n");

/**
 * Test locate-text-backward
 * - test ability to locate text, in specified order
 * - the third string comes after the second string, so this case should fail if the search head isn't moving backwards
 */
$threw = false;
try {
  InkScrape::checkBackBoundariesForText(array(
  'Suspendisse',
  'Vestibulum',
  'Etiam'), $string);
} catch (UnmatchedBoundaryException $e) {
  $threw = true;
}

echo((($threw && ($e->matchCount() == 2)) ? 'passed' : 'failed')." test (locate-text-backward)\n");

/**
 * Test locate-text-nonoverlap
 * - test ability to locate text, in specified order, with no overlapping of boundaries
 * - third string starts at the same position as the second string, so this case should fail if position isn't moved beyond previous successful matched boundary
 */
$threw = false;
try {
  $pos = 0;
  InkScrape::checkFrontBoundariesForText(array(
  'Lorem',
  'Suspendisse',
  'Suspend'), $string);
} catch (UnmatchedBoundaryException $e) {
  $threw = true;
}

echo((($threw && ($e->matchCount() == 2)) ? 'passed' : 'failed')." test (locate-text-nonoverlap)\n");

?>