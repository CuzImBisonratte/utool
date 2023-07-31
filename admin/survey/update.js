function updateValues() {
	$("#saving-overlay").css("height", "auto");
	$("#saving-overlay").css("transform", "translateY(0)");
	$("#saving-overlay").css("border-top-left-radius", "0");
	$("#saving-overlay").css("border-top-right-radius", "0");
	const start_time = new Date().getTime();
	var questions_array = [];
	$(".question").each(function () {
		var question = {};
		question.title = $(this).find(".question_title").text();
		question.type = this.classList[1];
		question.id = $(this).find(".question_title").attr("id").split("_")[2];
		question.options = {};
		switch (question.type) {
			case 'select':
				question.options.options = $(this).find("#question_" + question.id + "_options").val().split(" ");
			case 'line':
			case 'text':
				question.options.min = $(this).find("#question_" + question.id + "_min").val();
				question.options.max = $(this).find("#question_" + question.id + "_max").val();
				break;
			case 'multiplechoice':
			case 'dropdown':
			case 'toggle':
				question.options.options = $(this).find("#question_" + question.id + "_options").val().split(" ");
				break;
			case 'date':
			case 'time':
			case 'slider':
				question.options.min = $(this).find("#question_" + question.id + "_min").val();
				question.options.max = $(this).find("#question_" + question.id + "_max").val();
			case 'rating':
				question.options.default = $(this).find("#question_" + question.id + "_default").val();
				break;
		}
		if (question.options.options) question.options.options = question.options.options.filter((el) => { return el != "" || el != " " });
		questions_array.push(question);
	});
	window.setTimeout(() => {
		console.log(questions_array);
		$.ajax({
			url: "update.php",
			type: "POST",
			data: {
				survey_title: $("#nav-spacer").val(),
				questions: questions_array,
			},
			success: (data) => {
				if (data == "success") {
					// If time taken to update is less than 2.5 seconds, wait until 2.5 seconds have passed before hiding the overlay
					if (new Date().getTime() - start_time < 2500) {
						setTimeout(() => {
							$("#saving-overlay").css("transition", "all 1s ease 0s");
							$("#saving-overlay").css("transform", "translateY(100%)");
							$("#saving-overlay").css("border-top-left-radius", "100%");
							$("#saving-overlay").css("border-top-right-radius", "100%");
							action_button_save.style.color = "var(--text-color)";
							action_button_save.style.border = "0";
							window.onbeforeunload = null;
							setTimeout(() => {
								$("#saving-overlay").css("transition", "transform 1s ease 0s, border-radius 1s ease 0.25s");
								$("#saving-overlay").css("height", 0);
							}, 1000);
						}, 2500 - (new Date().getTime() - start_time));
					}
				} else {
					alert(data);
				}
			},
		});
	}, 1000);
}
