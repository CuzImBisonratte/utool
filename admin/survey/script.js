// General variables, functions
const action_button_save = document.getElementById("action_button-save");
const save_button = document.getElementById("action_button-save");
const results_button = document.getElementById("action_button-results");
const visibility_button = document.getElementById("action_button-visibility");
const delete_button = document.getElementById("action_button-delete");

function somethingChanged() {
    action_button_save.style.color = "var(--accent-color)";
    window.onbeforeunload = function (e) {
        e = e || window.event;

        action_button_save.style.border = "4px solid var(--accent-color)";

        // For IE and Firefox prior to version 4
        if (e) {
            e.returnValue = 'Sure?';
        }
        // For Safari
        return 'Sure?';
    };
}

// 
// Question title
// 

let edited_titles = [];

document.querySelectorAll(".question").forEach((item) => {
    // Get .question_title child element (not any other child element)
    let question_title = item.querySelector(".question_title");
    let eventlistener_list = [item];
    item.querySelectorAll("*").forEach((child) => eventlistener_list.push(child));
    // Get .options-container child element
    let options_container = item.querySelector(".options-container");
    // Add all child elements to eventlistener_list
    eventlistener_list.forEach((child) => {
        child.addEventListener("click", (e) => {
            if (e.target.closest(".options-container")) return;
            question_title.setAttribute("contenteditable", "true");
            question_title.focus();
            edited_titles.push(e.target.id);
            options_container.style.display = "grid";
            somethingChanged();
        });
    });
});

// 
// Result button
// 
results_button.addEventListener("click", () => location.assign("results.php?id=" + document.getElementById("survey_id").innerText));