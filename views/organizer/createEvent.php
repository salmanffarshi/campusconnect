<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/clubsModel.php";
require_once "../../models/categoriesModel.php";
$user = checkRole("organizer");
$club = findClubByOrganizer($user["user_id"]);
$categories = getAllCategories();

// A new event starts with empty boxes. After an error, show what was typed.
$eventId = 0;
$values = array(
    "title" => "",
    "description" => "",
    "categoryId" => "",
    "eventDate" => "",
    "startTime" => "",
    "endTime" => "",
    "venue" => "",
    "capacity" => ""
);

foreach ($values as $field => $value)
{
    if (isset($_GET[$field]))
    {
        $values[$field] = $_GET[$field];
    }
}

$pageTitle = "Create Event";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card card-narrow" style="max-width: 560px;">
    <h1>Create Event</h1>

    <?php if ($club == null): ?>

        <p class="error">You do not have a club yet, so you cannot create events. Please contact the administrator.</p>

    <?php else: ?>

        <p class="muted">
            Club: <strong><?php echo $club["club_name"]; ?></strong>.
            A new event is PENDING until the administrator approves it.
        </p>

        <?php require_once "eventForm.php"; ?>

    <?php endif; ?>
</div>

<?php require_once "../footer.php"; ?>
