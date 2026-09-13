<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/usersModel.php";
$user = checkRole("admin");

$search = "";
$role = "";

if (isset($_GET["search"]))
{
    $search = trim($_GET["search"]);
}

if (isset($_GET["role"]))
{
    $role = trim($_GET["role"]);
}

if ($role != "student" && $role != "organizer" && $role != "admin")
{
    $role = "";
}

$users = searchUsers($search, $role);

$pageTitle = "Users";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>Users</h1>
    <a class="btn" href="/campusconnect/views/admin/createOrganizer.php">+ Create Organizer</a>

    <form method="get" action="/campusconnect/views/admin/users.php">
        <div class="filters" style="margin-top: 16px;">
            <div class="field">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" placeholder="Name or email" value="<?php echo $search; ?>">
            </div>

            <div class="field">
                <label for="role">Role</label>
                <select name="role" id="role">
                    <option value="">All roles</option>
                    <option value="student" <?php if ($role == "student") { echo "selected"; } ?>>Students</option>
                    <option value="organizer" <?php if ($role == "organizer") { echo "selected"; } ?>>Organizers</option>
                    <option value="admin" <?php if ($role == "admin") { echo "selected"; } ?>>Admins</option>
                </select>
            </div>

            <div class="field">
                <input type="submit" class="btn" value="Search">
            </div>
        </div>
    </form>
</div>

<div class="card">
    <p class="muted"><?php echo count($users); ?> user(s) found</p>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($users as $person): ?>
            <tr>
                <td><?php echo $person["name"]; ?></td>
                <td><?php echo $person["email"]; ?></td>
                <td><?php echo ucfirst($person["role"]); ?></td>
                <td>
                    <?php if ($person["status"] == "active"): ?>
                        <span class="badge badge-approved">Active</span>
                    <?php else: ?>
                        <span class="badge badge-rejected">Inactive</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date("d M Y", strtotime($person["created_at"])); ?></td>
                <td class="actions">
                    <a class="btn btn-small" href="/campusconnect/views/admin/userDetails.php?userId=<?php echo $person["user_id"]; ?>">View</a>

                    <?php if ($person["user_id"] != $user["user_id"]): ?>
                        <form style="display: inline;" action="/campusconnect/controllers/userStatusControls.php" method="post">
                            <input type="hidden" name="userId" value="<?php echo $person["user_id"]; ?>">
                            <?php if ($person["status"] == "active"): ?>
                                <input type="hidden" name="status" value="inactive">
                                <input type="submit" class="btn btn-small btn-danger" value="Deactivate">
                            <?php else: ?>
                                <input type="hidden" name="status" value="active">
                                <input type="submit" class="btn btn-small btn-green" value="Activate">
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php require_once "../footer.php"; ?>
