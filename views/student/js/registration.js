function registerForEvent(eventId)
{
    sendRegistrationRequest("register", eventId);
}

function cancelRegistration(eventId)
{
    if (confirm("Cancel your registration for this event?") == false)
    {
        return;
    }

    sendRegistrationRequest("cancel", eventId);
}

function sendRegistrationRequest(action, eventId)
{
    const xhttp = new XMLHttpRequest();

    xhttp.onload = function ()
    {
        const response = JSON.parse(this.responseText);
        const messageBox = document.getElementById("message");

        if (response.success)
        {
            messageBox.innerHTML = "<div class='flash flash-success'>" + response.message + "</div>";

            // Reload after a moment so the page shows the new status.
            setTimeout(function ()
            {
                location.reload();
            }, 1200);
        }
        else
        {
            messageBox.innerHTML = "<div class='flash flash-error'>" + response.message + "</div>";
        }
    };

    xhttp.open("POST", "/campusconnect/controllers/registrationControls.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("action=" + action + "&eventId=" + eventId);
}
