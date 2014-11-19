<?php
require './classes/CQN_Name.php';

echo "\n===========================================";
$dave = new CQN_Name( 'mr dave bennett' );

echo "\n title    : " .  $dave->title;
echo "\n name     : " .  $dave->forename;
echo "\n surname  : " .  $dave->surname;
echo "\n full     : '" .  $dave->fullname() . "'";


echo "\n===========================================";


$dave = new CQN_Name( 'dave bennett' );

echo "\n title    : " .  $dave->title;
echo "\n name     : " .  $dave->forename;
echo "\n surname  : " .  $dave->surname;
echo "\n full     : '" .  $dave->fullname() . "'";

echo "\n===========================================";

$dave = new CQN_Name( 'dave' );

echo "\n title    : " .  $dave->title;
echo "\n name     : " .  $dave->forename;
echo "\n surname  : " .  $dave->surname;
echo "\n full     : '" .  $dave->fullname() . "'";


echo "\n";