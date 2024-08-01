<?php
use OpenAdmin\Admin\Widgets\Collapse;

$collapse = new Collapse();

$collapse->add('Bar', 'xxxxx');
$collapse->add('Orders', 'test');
$collapse->add('Last', 'last');

echo $collapse->render();