

const searchBox = document.getElementById("search");
const categorySelect = document.getElementById("categoryId");

searchBox.addEventListener("keyup", loadEvents);
categorySelect.addEventListener("change", loadEvents);

loadEvents();

function loadEvents()
{
    const search = searchBox.value;
    const categoryId = categorySelect.value;

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function ()
    {
        const response = JSON.parse(this.responseText);

        if (response.success == false)
        {
            document.getElementById("eventList").innerHTML = "<p class='error'>" + response.message + "</p>";
            return;
        }

        showEvents(response.data);
    };

    xhttp.open("GET", "/campusconnect/controllers/eventSearchControls.php?search=" + encodeURIComponent(search) + "&categoryId=" + categoryId);
    xhttp.send();
}

function showEvents(events)
{
    document.getElementById("resultCount").innerHTML = events.length + " event(s) found";

    if (events.length == 0)
    {
        document.getElementById("eventList").innerHTML = "<p class='muted'>No events match your search.</p>";
        return;
    }

    let html = "";

    for (let i = 0; i < events.length; i++)
    {
        const event = events[i];

        html += "<div class='event-card'>";
        html += "<h3>" + event.title + "</h3>";
        html += "<p><strong>Club:</strong> " + event.club_name + "</p>";
        html += "<p><strong>Category:</strong> " + event.category_name + "</p>";
        html += "<p><strong>Date:</strong> " + event.event_date + "</p>";
        html += "<p><strong>Time:</strong> " + event.start_time.substring(0, 5) + " - " + event.end_time.substring(0, 5) + "</p>";
        html += "<p><strong>Venue:</strong> " + event.venue + "</p>";
        html += "<a class='btn btn-small' style='margin-top: 10px;' href='/campusconnect/views/student/eventDetails.php?eventId=" + event.event_id + "'>View Details</a>";
        html += "</div>";
    }

    document.getElementById("eventList").innerHTML = html;
}
