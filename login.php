<?php

require_once __DIR__.'\\php\\session-check.php';
sessionCheck();

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- <link rel="stylesheet" href="/css/user-signup.css"> -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
  <style >

   .logo{
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
}
  .container {
    padding: 1em;
}
#company_name h1{
  color: White;
  font-weight: bold;
}

#logoRow {
    padding-top: 0 !important;
}

#logoCol {
    padding-right: 0 !important;
    padding-left: 0 !important;
}

#logoImg {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
    max-width: 150px;
    /* width: 150px; */
    /* position: relative;
    left: calc(50em - 75px); */
}

input {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 16px;
}

#btnRow {
    margin-top: 1em;
    margin-bottom: 0.5em !important;
}

#constraints {
    display: none;
    background-color: #dfdfdf;
    border-color: #dfdfdf !important;
    padding-bottom: 3vh;
}

.col-sm-4 {
    padding: 1vw;
}

#formCol {
    background-color: #dfdfdf;
    border: 0.1vw solid #dfdfdf;
    border-radius: 0.4vw;
}

#message {
    display:none;
    background: #dfdfdf;
    color: #000;
    padding: 0 !important;
    font-size: 0.75em !important;
}

.valid {
    color: green;
}

.invalid {
    color: red;
}

.material-icons {
    font-size: 18px !important;
    position: relative;
    top: 4px;
}
.col-sm-4 h3{
  text-align: center;
}

#wrongInput {
    color: red;
    display: none;
    margin-bottom: 0.5em;
    text-align: center !important
}

#idTaken {
    color: red;
    display: none;
    margin-bottom: 0.5em;
}

#loginLink {
    text-align: center;
    color: rgb(100, 110, 126);
}
</style>

</head>

<body>
  
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
    integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
  <script src="/js/login.js"></script>
  <div class="container">
     <div class="row justify-content-sm-center" id ="company_name">
     <img class="logo" src="logo1.png" alt="SuperTruper Inc.">
     </div>
    <!-- <div class="row justify-content-sm-center" id="logoRow">
      <div class="col-sm-4" id="logoCol">
        <img class="justify-content-sm-center" id="logoImg" src="logo1.png" alt="SuperTrouper.co logo (icon only)">
      </div>
    </div> -->
    <div class="row justify-content-sm-center">
      <div class="col-sm-4" id="formCol">
        <h3>Σύνδεση χρήστη</h3>
        <form>
          <div class="form-group">
            <label for="email">Διεύθυνση email</label>
            <input type="email" class="form-control" id="email" placeholder="name@example.com" minlength="1" required>
          </div>
          <div class="form-group">
            <label for="password">Κωδικός χρήστη</label>
            <input type="password" class="form-control" id="password" placeholder="Κωδικός" minlength="8" required>
          </div>
          <div class="form-group  text-center" id="btnRow">
            <button type="submit" class="btn btn-primary" id="submitButton">Σύνδεση</button>
          </div>
          <div class="form-group form-text" id="wrongInput">
            Παρακαλούμε ελέγξτε τα στοιχεία που έχετε εισάγει και προσπαθήστε ξανά!
          </div>
          <div class="form-group form-text" id="loginFailure">
            Ουπς! Τα στοιχεία που δώσατε δεν αντιστοιχούν σε κάποιο λογαριασμό. Μήπως πρέπει να εγγραφείτε; Αν είστε σίγουροι
            ότι έχετε ήδη εγγραφεί, παρακαλούμε προσπαθήστε πάλι.
          </div>
        </form>
        <div id="loginLink">
          Έχετε ήδη κάνει εγγραφή; Συνδεθείτε <a href="/login.html">εδώ</a>.
        </div>
      </div>
    </div>
  </div>

</body>
</html>