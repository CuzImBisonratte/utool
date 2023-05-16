function addUser() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;

    var data = {
        name: name,
        email: email
    };

    $.ajax({
        type: "POST",
        url: "users_add.php",
        data: data,
        success: function (data) {
            if (data != "success") console.log(data);
            else location.reload();
        },
        error: function (data) {
            alert("Error");
        }
    });   
}