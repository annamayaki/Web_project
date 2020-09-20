<?php

require_once __DIR__.'\\php\\session-check.php';
sessionCheck();

?>

<!DOCTYPE html>

<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Είσοδος | SuperTrouper</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&subset=greek">
  <link rel="stylesheet" href="/css/login-style.css">

</head>

<body>

  <div class="container">
    <div class="modal fade" id="failureModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Σφάλμα</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Υπήρξε ένα πρόβλημα κατά τη μεταφορά απαραίτητων δεδομένων. Παρακαλούμε προσπαθήστε
            πάλι σε μερικά δευτερόλεπτα.
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-sm-center">
      <img class="logo" src="/img/logo.png" alt="SuperTrouper.co">
    </div>
    <div class="row justify-content-sm-center">
      <div class="col-md-4" id="formCol">
        <h3>Σύνδεση Χρήστη</h3>
        <form>
          <div class="form-group">
            <label for="email">Διεύθυνση Email</label>
            <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
          </div>
          <div class="form-group">
            <label for="password">Κωδικός Χρήστη</label>
            <input type="password" class="form-control" id="password" placeholder="password">
          </div>
          <div class="form-group text-center" id="btnRow">
            <button id="toggleVisibility" class="btn btn-info material">
              <i class="material-icons">visibility</i>
              <i class="material-icons">visibility_off</i>
              <span id="showPass">Εμφάνιση Κωδικού</span>
              <span id="hidePass">Απόκρυψη Κωδικού</span>
            </button>
            <button type="submit" class="btn btn-primary" id="submitButton">Σύνδεση</button>
          </div>
          <div class="form-group form-text" id="wrongInput">
            Παρακαλούμε ελέγξτε τα στοιχεία που έχετε εισάγει και προσπαθήστε ξανά!
          </div>
          <div class="form-group form-text" id="idTaken">
            Ουπς! Τα στοιχεία που δώσατε αντιστοιχούν σε ήδη υπάρχοντα λογαριασμό. Μήπως πρέπει να συνδεθείτε;
            Αν είστε σίγουροι ότι δεν έχετε ήδη εγγραφεί, παρακαλούμε προσπαθήστε πάλι.
          </div>
        </form>
        <div id="signupLink">
          Δεν έχετε ακόμα λογαριασμό; Εγγραφείτε <a href="/user/signup.php">εδώ</a>.
        </div>
      </div>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>  
  <script src="/js/login.js"></script>

</body>

</html>