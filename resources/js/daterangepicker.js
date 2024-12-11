import "daterangepicker";
import "daterangepicker/daterangepicker.css";

document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: "UTC",
        initialView: "dayGridWeek",
        headerToolbar: {
            left: "prev,next",
            center: "title",
            right: "dayGridWeek,dayGridDay",
        },
        editable: true,
        events: "https://fullcalendar.io/api/demo-feeds/events.json",
    });

    calendar.render();
});
