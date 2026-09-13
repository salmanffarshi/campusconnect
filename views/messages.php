<?php

if (isset($_GET["generalMessage"]))
{
    echo "<div class='flash flash-success'>".$_GET["generalMessage"]."</div>";
}

if (isset($_GET["generalErr"]))
{
    echo "<div class='flash flash-error'>".$_GET["generalErr"]."</div>";
}

?>
