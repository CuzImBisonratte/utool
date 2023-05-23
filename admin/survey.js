// General variables, functions
const action_button_save = document.getElementById("action_button-save");

function somethingChanged() {
    action_button_save.style.color = "var(--accent-color)";
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
    // Add all child elements to eventlistener_list
    eventlistener_list.forEach((child) => {
        child.addEventListener("click", (e) => {
            question_title.setAttribute("contenteditable", "true");
            question_title.focus();
            edited_titles.push(e.target.id);
            somethingChanged();
        });
    });
});