<?php
require_once "authCheck.php";
require_once "../models/clubsModel.php";
require_once "../models/categoriesModel.php";
require_once "../models/eventsModel.php";

$user = checkRole("organizer");
$club = findClubByOrganizer($user["user_id"]);

if ($club == null)
{
    header("Location: /campusconnect/views/organizer/statistics.php?generalErr=" . urlencode("You do not have a club yet. Please contact the administrator."));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $eventId = (int) $_POST["eventId"];
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $categoryId = (int) $_POST["categoryId"];
    $eventDate = trim($_POST["eventDate"]);
    $startTime = trim($_POST["startTime"]);
    $endTime = trim($_POST["endTime"]);
    $venue = trim($_POST["venue"]);
    $capacity = trim($_POST["capacity"]);

    if ($eventId > 0)
    {
        $event = findEventById($eventId);

        if ($event == null || $event["club_id"] != $club["club_id"])
        {
            header("Location: /campusconnect/views/organizer/myEvents.php?generalErr=" . urlencode("You can only edit events of your own club."));
            exit();
        }

        if ($event["status"] != "PENDING" && $event["status"] != "REJECTED")
        {
            header("Location: /campusconnect/views/organizer/myEvents.php?generalErr=" . urlencode("Only pending or rejected events can be edited."));
            exit();
        }
    }

    $hasErr = false;
    $titleErr = "";
    $descriptionErr = "";
    $categoryErr = "";
    $dateErr = "";
    $timeErr = "";
    $venueErr = "";
    $capacityErr = "";

    if (empty($title))
    {
        $titleErr = "Title cannot be empty.";
        $hasErr = true;
    }
    else if (strlen($title) > 200)
    {
        $titleErr = "Title cannot be longer than 200 letters.";
        $hasErr = true;
    }

    if (empty($description))
    {
        $descriptionErr = "Description cannot be empty.";
        $hasErr = true;
    }
    else if (strlen($description) > 2000)
    {
        $descriptionErr = "Description cannot be longer than 2000 letters.";
        $hasErr = true;
    }

    if (findCategoryById($categoryId) == null)
    {
        $categoryErr = "Please choose a category.";
        $hasErr = true;
    }

    if (empty($eventDate))
    {
        $dateErr = "Please choose a date.";
        $hasErr = true;
    }
    else if ($eventDate < date("Y-m-d"))
    {
        $dateErr = "The event date cannot be in the past.";
        $hasErr = true;
    }

    if (empty($startTime) || empty($endTime))
    {
        $timeErr = "Please choose a start time and an end time.";
        $hasErr = true;
    }
    else if ($endTime <= $startTime)
    {
        $timeErr = "The end time must be after the start time.";
        $hasErr = true;
    }

    if (empty($venue))
    {
        $venueErr = "Venue cannot be empty.";
        $hasErr = true;
    }
    else if (strlen($venue) > 200)
    {
        $venueErr = "Venue cannot be longer than 200 letters.";
        $hasErr = true;
    }

    if ($capacity == "")
    {
        $capacityErr = "Capacity cannot be empty.";
        $hasErr = true;
    }
    else if (filter_var($capacity, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1, "max_range" => 10000))) === false)
    {
        $capacityErr = "Capacity must be a whole number from 1 to 10000.";
        $hasErr = true;
    }

    if ($hasErr)
    {
        $page = "createEvent.php?";

        if ($eventId > 0)
        {
            $page = "editEvent.php?eventId=" . $eventId . "&";
        }

        $url = "Location: /campusconnect/views/organizer/" . $page
             . "title=" . urlencode($title)
             . "&description=" . urlencode($description)
             . "&categoryId=" . $categoryId
             . "&eventDate=" . urlencode($eventDate)
             . "&startTime=" . urlencode($startTime)
             . "&endTime=" . urlencode($endTime)
             . "&venue=" . urlencode($venue)
             . "&capacity=" . urlencode($capacity)
             . "&titleErr=" . urlencode($titleErr)
             . "&descriptionErr=" . urlencode($descriptionErr)
             . "&categoryErr=" . urlencode($categoryErr)
             . "&dateErr=" . urlencode($dateErr)
             . "&timeErr=" . urlencode($timeErr)
             . "&venueErr=" . urlencode($venueErr)
             . "&capacityErr=" . urlencode($capacityErr);
        header($url);
        exit();
    }

    if ($eventId > 0)
    {
        updateEvent($eventId, $categoryId, $title, $description, $eventDate, $startTime, $endTime, $venue, (int) $capacity);
        $message = "Event updated and sent to the administrator for approval again.";
    }
    else
    {
        $newId = createEvent($club["club_id"], $categoryId, $title, $description, $eventDate, $startTime, $endTime, $venue, (int) $capacity);

        if ($newId <= 0)
        {
            header("Location: /campusconnect/views/organizer/myEvents.php?generalErr=" . urlencode("The event could not be saved. Please try again."));
            exit();
        }

        $message = "Event created. Students will see it after the administrator approves it.";
    }

    header("Location: /campusconnect/views/organizer/myEvents.php?generalMessage=" . urlencode($message));
    exit();
}

?>
