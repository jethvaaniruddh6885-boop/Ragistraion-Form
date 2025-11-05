$("#registerForm").on("submit", function(e){
    e.preventDefault();

    $(".text-red-500").text(""); // clear old errors

    const fullName = $("#fullName").val().trim();
    const username = $("#username").val().trim();
    const email = $("#email").val().trim();
    const phone = $("#phone").val().trim();
    const password = $("#password").val();
    const confirmPassword = $("#confirmPassword").val();

    let errors = {};

    if(fullName === "") errors.fullName = "Full Name is required";
    if(username === "") errors.username = "Username is required";
    if(email === "") errors.email = "Email is required";
    if(phone === "") errors.phone = "Phone is required";
    else if(!/^\d{10,}$/.test(phone)) errors.phone = "Phone must be numeric and at least 10 digits";
    if(password === "") errors.password = "Password is required";
    if(confirmPassword === "") errors.confirmPassword = "Confirm your password";
    if(password !== "" && confirmPassword !== "" && password !== confirmPassword) errors.confirmPassword = "Passwords do not match";

    for(let key in errors) $("#"+key+"Error").text(errors[key]);
    if(Object.keys(errors).length > 0) return;

    $.ajax({
        url: "insert.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(res){
            if(res.success){
                alert(res.success);
                $("#registerForm")[0].reset();
            } else {
                for(let key in res){
                    if($("#"+key+"Error").length){
                        $("#"+key+"Error").text(res[key]);
                    } else if(key==="general"){
                        alert(res[key]);
                    }
                }
            }
        },
        error: function(xhr, status, error){
            console.error("AJAX error:", error);
            console.log("ResponseText:", xhr.responseText);
            alert("⚠️ Server error. Check console for details.");
        }
    });
});
fetch('http://localhost:8081/User_RagistraionForm/forgot_password.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({email: 'user@example.com'})
})
.then(res => res.json())
.then(data => console.log(data));
