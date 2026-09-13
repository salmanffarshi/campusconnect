<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/categoriesModel.php";
$user = checkRole("student");
$categories = getAllCategories();
$pageTitle = "Events";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>Browse Events</h1>
    <p class="muted">Only approved, upcoming events are shown. The list updates while you type.</p>

    <div class="filters">
        <div class="field">
            <label for="search">Search</label>
            <input type="text" id="search" placeholder="Title, venue or club">
        </div>

        <div class="field">
            <label for="categoryId">Category</label>
            <select id="categoryId">
                <option value="0">All categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category["category_id"]; ?>"><?php echo $category["category_name"]; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<p id="resultCount" class="muted"></p>
<div id="eventList" class="event-grid"></div>

<script src="/campus/views/student/js/events.js"></script>

<?php require_once "../footer.php"; ?>
