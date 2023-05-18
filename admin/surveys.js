const add_survey_timespan_input_active = document.getElementById("timespan");
const add_survey_timespan_container = document.getElementById("timespan_container");

add_survey_timespan_input_active.addEventListener("change", function () {
    if (add_survey_timespan_input_active.checked) add_survey_timespan_container.style.display = "flex";
    else add_survey_timespan_container.style.display = "none";
});

function addSurvey() {
    var title = document.getElementById("title").value;
    var description = document.getElementById("description").value;
    var timespan = document.getElementById("timespan").checked;
    var timespan_start = document.getElementById("timespan_start").value;
    var timespan_end = document.getElementById("timespan_end").value;

    var data = {
        title: title,
        description: description,
        timespan: timespan
    };

    if (timespan) {
        data.timespan_start = timespan_start;
        data.timespan_end = timespan_end;
    }

    $.ajax({
        type: "POST",
        url: "surveys_add.php",
        data: data,
        success: function (data) {
            if (data != "success") console.log(data);
            else location.reload();
        }
    });
}