

const eventSelect = document.getElementById("eventId");

if (eventSelect != null)
{
    eventSelect.addEventListener("change", loadStatistics);
}

function loadStatistics()
{
    const eventId = eventSelect.value;
    const statsArea = document.getElementById("statsArea");
    const messageBox = document.getElementById("message");

    messageBox.innerHTML = "";

    if (eventId == "0")
    {
        statsArea.style.display = "none";
        return;
    }

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function ()
    {
        const response = JSON.parse(this.responseText);

        if (response.success == false)
        {
            statsArea.style.display = "none";
            messageBox.innerHTML = "<div class='flash flash-error'>" + response.message + "</div>";
            return;
        }

        const stats = response.data;

        document.getElementById("registered").innerHTML = stats.registered;
        document.getElementById("present").innerHTML = stats.present;
        document.getElementById("percentage").innerHTML = stats.attendancePercentage + "%";
        document.getElementById("feedbackCount").innerHTML = stats.feedbackCount;
        document.getElementById("averageRating").innerHTML = stats.averageRating + " / 5";

        statsArea.style.display = "block";
    };

    xhttp.open("GET", "/campusconnect/controllers/eventStatsControls.php?eventId=" + eventId);
    xhttp.send();
}
