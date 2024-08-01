<?php
use OpenAdmin\Admin\Widgets\Box;

$box = new Box('Box Title', 'Box content', 'Box footer');

// you can also use:
$box->header("My Footer");
$box->content("My Conten");
$box->footer("My Footer");

//box options
$box->removable();
$box->collapsable();
$box->styles(["border"=>"1px solid #FFAA00","margin-top"=>"20px"]);

echo $box; // this use the magic __toString function that calls the render() functions
// you can also use:
//$box->render();