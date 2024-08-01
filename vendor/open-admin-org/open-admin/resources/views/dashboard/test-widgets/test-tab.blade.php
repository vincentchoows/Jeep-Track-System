<?php
use OpenAdmin\Admin\Widgets\Tab;
use OpenAdmin\Admin\Widgets\Table;

$tab = new Tab();

$pie = "test tab";

$tab->add('Pie', $pie);
$tab->add('Table', new Table());
$tab->add('Text', 'blablablabla....');

echo $tab->render();


?>

