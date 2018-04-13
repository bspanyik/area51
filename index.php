<?php
require 'vendor/autoload.php';

$roomFactory  = new Area51\Factory\RoomFactory();
$robotFactory = new Area51\Factory\RobotFactory($roomFactory);
$robot = $robotFactory->make('akárki@valami.hu');

echo 'Welcome to Area 51...';
echo '<pre>' . print_r($robot, true) . '</pre>';

