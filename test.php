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
  InkScrape::checkFrontBoundariesForText(array(
  'Lorem',
  'Suspendisse',
  'Suspend'), $string);
} catch (UnmatchedBoundaryException $e) {
  $threw = true;
}

echo((($threw && ($e->matchCount() == 2)) ? 'passed' : 'failed')." test (locate-text-nonoverlap)\n");

/**
 * Test locate-bounded-text
 * - test ability to locate text with front and back boundaries
 */
$pos = 0;
$matched = false;
if(InkScrape::boundedText(array('Lorem','consectetur','tortor'), array('Suspendisse','Etiam','pellentesque'), $string) == " quam, ") {
  $matched = true;
}
echo(($matched ? 'passed' : 'failed')." test (locate-bounded-text)\n");

/**
 * Test locate-bounded-text-front
 * - test ability to locate text with front and back boundaries
 */
$pos = 0;
$matched = false;
if(InkScrape::textWithFrontBoundaries(array('Lorem','Etiam','Suspendisse','tincidunt'), $string) == " auctor.") {
  $matched = true;
}
echo(($matched ? 'passed' : 'failed')." test (locate-bounded-text-front)\n");

/**
 * Test locate-bounded-text-front-arbitrary
 * - test ability to locate text with front boundaries, starting from an arbitrary position
 */
$pos = strpos($string, "laoreet ")+strlen("laoreet ");
$matched = false;
if(InkScrape::textWithFrontBoundaries(array('quam'), $string, $pos) == " vel metus. Suspendisse eu risus id justo tincidunt auctor.") {
  $matched = true;
}
echo(($matched ? 'passed' : 'failed')." test (locate-bounded-text-front-arbitrary)\n");

/**
 * Test locate-bounded-text-back
 * - test ability to locate text with front and back boundaries
 */
$pos = 0;
$matched = false;
if(InkScrape::textWithBackBoundaries(array('Suspendisse','Etiam','consectetur','ipsum'), $string) == "Lorem ") {
  $matched = true;
}
echo(($matched ? 'passed' : 'failed')." test (locate-bounded-text-back)\n");

/**
 * Test locate-bounded-text-back-arbitrary
 * - test ability to locate text with back boundaries, starting from an arbitrary position
 */
$pos = strpos($string, ", pellentesque")+strlen(", pellentesque");
$matched = false;
if(InkScrape::textWithbackBoundaries(array('quam'), $string, $pos) == "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tortor ") {
  $matched = true;
}
echo(($matched ? 'passed' : 'failed')." test (locate-bounded-text-back-arbitrary)\n");

?>