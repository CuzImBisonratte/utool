function submitForm() {
    const question_answers = [];

    for (const i in questions) {
        const question = questions[i];
        question_element = document.getElementById("question_" + question.id);
        switch (question.type) {
            case "line":
            case "text":
            case "date":
            case "time":
                question_answers.push({id: question.id, answer: question_element.value});
                break;
            case "multiplechoice":
                const checked = document.querySelector('.question_'+question.id+':checked');
                if (checked) question_answers.push({id: question.id, answer: checked.id.toString().replace("question_"+question.id+"_", "")});
                else question_answers.push({id: question.id, answer: ""}); 
                break;
            case "select":
                const allChecked = document.querySelectorAll('.question_'+question.id+':checked');
                var checked_values = [];
                for (const i in allChecked) {
                    if (allChecked[i].value) checked_values.push(allChecked[i].id.toString().replace("question_"+question.id+"_", ""));
                }
                question_answers.push({id: question.id, answer: checked_values});
                break;
            case "dropdown":
                question_answers.push({id: question.id, answer: question_element.options[question_element.selectedIndex].value});
                break;
            case "slider":
                question_answers.push({id: question.id, answer: question_element.value});
                break;
            case "toggle":
                const toggleChecked = document.querySelector('.question_'+question.id+':checked');
                if (toggleChecked) question_answers.push({id: question.id, answer: toggleChecked.id.toString().replace("question_"+question.id+"_", "")});
                else question_answers.push({id: question.id, answer: ""}); 
                break;
            case "rating":
                question_answers.push({id: question.id, answer: question.changes.value});
                break;
        }
    }

    console.log(question_answers);
}