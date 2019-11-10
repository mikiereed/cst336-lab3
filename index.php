<!DOCTYPE html>
<html>
    <head>
        <title> Sign Up Page </title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body>
        <h1> Sign Up </h1>
        
        <form id="signupForm" method="post" action="welcome.html">
            First Name: <input type="text" name="fName"/><br>
            Last Name: <input type="text" name="lName"/><br>
            Gender: <input type="radio" name="gender" value="m"> Male
                <input type="radio" name="gender" value="f"> Female <br><br>
        
            Zip Code: <input type="text" name="zip" id="zip"><br>
            City: <span id="city"></span><br>
            Latitude: <span id="latitude"></span><br>
            Longitude: <span id="longitude"></span><br>
            State: 
            <select id="state" name="state">
                <option value="">Select One</option>
            </select><br />
        
            Select a County: <select id="county"></select><br><br>
        
            Desired Username: <input type="text" id="username" name="username"><br>
                <span id="usernameError"></span><br>
            Password: <input type="password" id="password" name="password"><br>
                <span id="passwordTooShort"></span><br /><br>
            Password Again: <input type="password" id="passwordAgain"><br>
                <span id="passwordAgainError"></span><br /><br>
            <input type="submit" value="Sign Up!">
        </form>
        
        <script>
            var usernameAvailable = false;
            //get list of states
            $.ajax({
                    method: "GET",
                    url: "https://cst336.herokuapp.com/projects/api/state_abbrAPI.php",
                    dataType: "json",
                    //data: { "state": $("#state").val() },
                    success: function(result,status) {
                        //alert(result[0].county);
                        $("#state").html("<option> Select one </option>");
                        for (let i=0; i < result.length; i++) {
                            $("#state").append("<option>" + result[i].state + "</option>");    
                        }
                    }
                }); //ajax list states
            
            //displaying city from API after typing a zip code
            $("#zip").on("change", function() {
                //alert($("#zip").val());
                $("#city").html("Zip Code Not Found");
                $("#latitude").html("");
                $("#longitude").html("");
                $.ajax({
                    method: "GET",
                    url: "https://cst336.herokuapp.com/projects/api/cityInfoAPI.php",
                    dataType: "json",
                    data: { "zip": $("#zip").val() },
                    success: function(result,status) {
                        //alert(result.city);
                        $("#city").html(result.city);
                        $("#city").css("color", "black");
                        $("#latitude").html(result.latitude);
                        $("#longitude").html(result.longitude);
                    }
                }); //ajax
            }); //zip
            
            $("#state").on("change", function() {
                //alert($("#state").val());
                $.ajax({
                    method: "GET",
                    url: "https://cst336.herokuapp.com/projects/api/countyListAPI.php",
                    dataType: "json",
                    data: { "state": $("#state").val() },
                    success: function(result,status) {
                        //alert(result[0].county);
                        $("#county").html("<option> Select one </option>");
                        for (let i=0; i < result.length; i++) {
                            $("#county").append("<option>" + result[i].county + "</option>");    
                        }
                    }
                }); //ajax
            }); //state
            
            $("#username").change(function() {
                //alert($("#username").val());
                $.ajax({
                    method: "GET",
                    url: "https://cst336.herokuapp.com/projects/api/usernamesAPI.php",
                    dataType: "json",
                    data: { "username": $("#username").val() },
                    success: function(result,status) {
                        if(result.available) {
                            $("#usernameError").html("Username is available!");
                            $("#usernameError").css("color", "green");
                            usernameAvailable = true;
                        } else {
                            $("#usernameError").html("Username is unavailable!");
                            $("#usernameError").css("color", "red");
                            usernameAvailable = false;
                        }
                    }
                }); //ajax
            }); //username
            
            $("#password").change(function() {
                if ($("#password").val().length < 6) {
                    $("#passwordTooShort").html("Password Must be at least 6 characters");
                    $("#passwordTooShort").css("color", "red");
                } else {
                    $("#passwordTooShort").html("");
                }
            })
            
            $("#username").on("submit", function(e) {
                alert("Submitting form...")
                if (!isFormValid()) {
                    e.preventDefault();
                }; //isFormValid
            }); //submit
            
            function isFormValid() {
                isValid = true;
                if (!usernameAvailable) {
                    isValid = false;
                }
                if ($("#username").val().length == 0) {
                    isValid = false;
                    $("#usernameError").html("Username is required");
                }
                if ($("#password").val() != $("#passwordAgain").val()) {
                    $("#passwordAgainError").html("Retype Password");
                    isValid = false;
                }
                if ($("#latitude").val() == "") {
                    isValid = false;
                }
                return isValid;
            }
        </script>
    </body>
</html>