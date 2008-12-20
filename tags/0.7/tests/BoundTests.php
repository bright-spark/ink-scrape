<?php

error_reporting(E_ALL | E_STRICT);

include("InkScrape.php");

$inst = new InkScrape();
$inst->data = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tortor quam, pellentesque at, blandit id, consequat malesuada, lectus. Etiam laoreet quam vel metus. Suspendisse eu risus id justo tincidunt auctor.";

/**
 * Test locate-text-general
 * - test ability to locate text (not necessarily in any order)
 */
$inst->position = 0;
$inst->checkFrontBoundaries(array(
'Lorem',
'Vestibulum',
'Etiam'));
echo("passed test (locate-text-general)\n");

/**
 * Test locate-text-forward
 * - test ability to locate text, in specified order
 * - the third string comes before the second string, so this case should fail if position isn't stored
 */
$inst->position=0;
$return_value = $inst->checkFrontBoundaries(array(
  'Lorem',
  'Etiam',
  'Vestibulum'));

echo(((!$return_value && ($inst->lastBoundaryChecker()->totalBoundariesMatched() == 2)) ? 'passed' : 'FAILED')." test (locate-text-forward)\n");

/**
 * Test locate-text-nonoverlap
 * - test ability to locate text, in specified order, with no overlapping of boundaries
 * - third string starts at the same position as the second string, so this case should fail if position isn't moved beyond previous successful matched boundary
 */
$inst->position=0;
$return_value = $inst->checkFrontBoundaries(array(
  'Lorem',
  'Suspendisse',
  'Suspend'));

echo((!$return_value ? 'passed' : 'FAILED')." test (locate-text-nonoverlap)\n");

/**
 * Test locate-bounded-text
 * - test ability to locate text with front and back boundaries
 */
$inst->position=0;
$matched = true;
$matched = $matched && ($inst->boundedText(array('Lorem','consectetur','tortor'), array('pellentesque','Etiam','Suspendisse')) == " quam, ");
$inst->position=0;
$matched = $matched && ($inst->boundedText(array('elit'), array('quam')) == ". Vestibulum tortor ");
echo(($matched ? 'passed' : 'FAILED')." test (locate-bounded-text)\n");

?>