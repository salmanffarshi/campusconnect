<form action="/campusconnect/controllers/eventControls.php" method="post">

    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">

    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="<?php echo $values["title"]; ?>">
    <span class="error"><?php if (isset($_GET["titleErr"])) { echo $_GET["titleErr"]; } ?></span>

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="4"><?php echo $values["description"]; ?></textarea>
    <span class="error"><?php if (isset($_GET["descriptionErr"])) { echo $_GET["descriptionErr"]; } ?></span>

    <label for="categoryId">Category</label>
    <select name="categoryId" id="categoryId">
        <option value="">Choose a category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category["category_id"]; ?>" <?php if ($values["categoryId"] == $category["category_id"]) { echo "selected"; } ?>>
                <?php echo $category["category_name"]; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <span class="error"><?php if (isset($_GET["categoryErr"])) { echo $_GET["categoryErr"]; } ?></span>

    <label for="eventDate">Date</label>
    <input type="date" name="eventDate" id="eventDate" value="<?php echo $values["eventDate"]; ?>">
    <span class="error"><?php if (isset($_GET["dateErr"])) { echo $_GET["dateErr"]; } ?></span>

    <label for="startTime">Start Time</label>
    <input type="time" name="startTime" id="startTime" value="<?php echo $values["startTime"]; ?>">

    <label for="endTime">End Time</label>
    <input type="time" name="endTime" id="endTime" value="<?php echo $values["endTime"]; ?>">
    <span class="error"><?php if (isset($_GET["timeErr"])) { echo $_GET["timeErr"]; } ?></span>

    <label for="venue">Venue</label>
    <input type="text" name="venue" id="venue" value="<?php echo $values["venue"]; ?>">
    <span class="error"><?php if (isset($_GET["venueErr"])) { echo $_GET["venueErr"]; } ?></span>

    <label for="capacity">Capacity</label>
    <input type="number" name="capacity" id="capacity" min="1" value="<?php echo $values["capacity"]; ?>">
    <span class="error"><?php if (isset($_GET["capacityErr"])) { echo $_GET["capacityErr"]; } ?></span>

    <?php if ($eventId > 0): ?>
        <input type="submit" class="btn" value="Save and Send for Approval">
    <?php else: ?>
        <input type="submit" class="btn" value="Create Event">
    <?php endif; ?>

    <a class="btn btn-link" href="/campusconnect/views/organizer/myEvents.php">Cancel</a>
</form>
