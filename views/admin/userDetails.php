<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/clubsModel.php";
require_once "../../models/registrationsModel.php";
$user = checkRole("admin");

$userId = 0;

if (isset($_GET["userId"]))
{
    $userId = (int) $_GET["userId"];
}

$person = findUserById($userId);

if ($person == null)
{
    header("Location: /campusconnect/views/admin/users.php?generalErr=" . urlencode("User not found."));
    exit();
}

$club = null;
$registrations = array();

if ($person["role"] == "organizer")
{
    $club = findClubByOrganizer($person["user_id"]);
}

if ($person["role"] == "student")
{
    $registrations = getRegistrationsByStudent($person["user_id"]);
}

$pageTitle = "User Details";
require_once "../header.php";
?>

<div class="card">
    <a href="/campusconnect/views/admin/users.php">&larr; Back to users</a>
    <h1 style="margin-top: 12px;"><?php echo $person["name"]; ?></h1>

    <table class="details-table">
        <tr>
            <th>Email</th>
            <td><?php echo $person["email"]; ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?php echo ucfirst($person["role"]); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <?php if ($person["status"] == "active"): ?>
                    <span class="badge badge-approved">Active</span>
                <?php else: ?>
                    <span class="badge badge-rejected">Inactive</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Joined</th>
            <td><?php echo date("d M Y", strtotime($person["created_at"])); ?></td>
        </tr>

        <?php if ($person["role"] == "organizer"): ?>
        <tr>
            <th>Club</th>
            <td>
                <?php if ($club != null): ?>
                    <strong><?php echo $club["club_name"]; ?></strong><br>
                    <span class="muted"><?php echo $club["description"]; ?></span>
                <?php else: ?>
                    <span class="muted">No club assigned</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endif; ?>
    </table>
</div>

<?php if ($person["role"] == "student"): ?>
<div class="card">
    <h2>Registrations (<?php echo count($registrations); ?>)</h2>

    <?php if (count($registrations) == 0): ?>
        <p class="muted">This student has not registered for any event.</p>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Event</th>
                <th>Club</th>
                <th>Date</th>
                <th>Registration</th>
                <th>Attendance</th>
                <th>Rating</th>
            </tr>
            <?php foreach ($registrations as $row): ?>
            <tr>
                <td><?php echo $row["title"]; ?></td>
                <td><?php echo $row["club_name"]; ?></td>
                <td><?php echo date("d M Y", strtotime($row["event_date"])); ?></td>
                <td><?php echo ucfirst(strtolower($row["registration_status"])); ?></td>
                <td>
                    <?php
                    if ($row["attendance_status"] == null)
                    {
                        echo "<span class='muted'>Not marked</span>";
                    }
                    else
                    {
                        echo ucfirst(strtolower($row["attendance_status"]));
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row["rating"] == null)
                    {
                        echo "<span class='muted'>-</span>";
                    }
                    else
                    {
                        echo $row["rating"] . " / 5";
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php require_once "../footer.php"; ?>
