let rating_stars = document.querySelectorAll('.rating_star');

rating_stars.forEach(function(star) {
    // Eventlistener
    star.addEventListener('click', setRating);
});

function setRating(ev){
    const parentId = ev.target.parentElement.id;
    const starnum = parentId.split('_')[2];
    const questionId = parentId.split('_')[1];
    for (let i = 1; i <= 5; i++) {
        if(i <= starnum) document.getElementById("question_"+questionId+"_"+i).innerHTML = '<i class="fas fa-star"></i>';
        else document.getElementById("question_"+questionId+"_"+i).innerHTML = '<i class="far fa-star"></i>';        
    }
    questions[questionId].changes.value = starnum;
}