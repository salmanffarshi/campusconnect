function markAttendance(registrationId, status)
{
    const xhttp = new XMLHttpRequest();

    xhttp.onload = function ()
    {
        const response = JSON.parse(this.responseText);
        const messageBox = document.getElementById("message");

        if (response.success)
        {
            let badge = "<span class='badge badge-approved'>Present</span>";

            if (status == "ABSENT")
            {
                badge = "<span class='badge badge-rejected'>Absent</span>";
            }

            document.getElementById("status-" + registrationId).innerHTML = badge;
            messageBox.innerHTML = "<div class='flash flash-success'>" + response.message + "</div>";
        }
        else
        {
            messageBox.innerHTML = "<div class='flash flash-error'>" + response.message + "</div>";
        }
    };

    xhttp.open("POST", "/campusconnect/controllers/attendanceControls.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("registrationId=" + registrationId + "&status=" + status);
}
