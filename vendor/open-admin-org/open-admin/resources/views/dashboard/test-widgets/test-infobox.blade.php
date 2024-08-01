
<style>
    .custom-red {
        color: #BA274A; /* Red */
    }

    .custom-blue {
        color: #3185FC; /* Blue */
    }

    .custom-orange {
        color: #F29559; /* Orange */
    }

    .custom-purple {
        color: #BA699E; /* Purple */
    }
</style>

<?php
use OpenAdmin\Admin\Widgets\InfoBox;



//infobox attributes
//name, icon, link, color, info, link_text

//infobox 1: total pending application
$infoBox = new InfoBox('New Users', 'users', 'red', '/admin/users', '1024');
$infoBox->setID("user-infobox");
echo $infoBox->render();

//infobox 1: total monthly application
$infoBoxError = new InfoBox('Error', 'users', 'warning', '/admin/users', '22');
$infoBoxError->link("/admin/users?filter=error");
$infoBoxError->link_text("Show error users");
$infoBoxError->color("danger");
$infoBoxError->title("Error users");
$infoBoxError->icon("user-slash");
$infoBoxError->info("23");
echo $infoBoxError->render();



?>

