<?php

require_once __DIR__.'\\..\\php\\session-check.php';
sessionCheck();

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/user-signup.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

</head>

<body>

  <div class="container">
    <div class="row justify-content-sm-center" id="logoRow">
      <div class="col-sm-4" id="logoCol">
        <img class="justify-content-sm-center" id="logoImg" src="/img/logo_img.png"
          alt="SuperTrouper.co logo (icon only)">
      </div>
    </div>
    <div class="row justify-content-sm-center">
      <div class="col-sm-4" id="formCol">
        <h3>Δημιουργία Νέου Χρήστη</h3>
        <form>
          <div class="form-group">
            <label for="firstname">Όνομα</label>
            <input class="form-control" id="firstname" placeholder="Ιωάννης" minlength="1" required>
          </div>
          <div class="form-group">
            <label for="lastname">Επίθετο</label>
            <input class="form-control" id="lastname" placeholder="Μηλόσπορος" minlength="1" required>
          </div>
          <div class="form-group">
            <label for="username">Όνομα χρήστη</label>
            <input class="form-control" id="username" placeholder="jAppleseed" minlength="1" required>
          </div>
          <div class="form-group">
            <label for="email">Διεύθυνση email</label>
            <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
          </div>
          <div class="form-group">
            <label for="password">Κωδικός χρήστη</label>
            <input type="password" class="form-control" id="password" placeholder="Κωδικός"
              pattern="(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}"
              title="Ο κωδικός πρέπει να αποτελείται από τουλάχιστον 8 χαρακτήρες και να περιέχει τουλάχιστον ένα κεφαλαίο γράμμα και ένα σύμβολο."
              required>
          </div>
          <div class="card" id="constraints">
            <div class="card-body" id="message">
              <div id="capital" class="invalid"><i class="material-icons">close</i> Ένα <b>κεφαλαίο</b> γράμμα</div>
              <div id="number" class="invalid"><i class="material-icons">close</i> Ένας <b>αριθμός</b></div>
              <div id="char" class="invalid"><i class="material-icons">close</i> Ένας ειδικός <b>χαρακτήρας</b>
                (!@#$%^&*)</div>
              <div id="length" class="invalid"><i class="material-icons">close</i> Τουλάχιστον <b>8 χαρακτήρες</b></div>
            </div>
          </div>
          <div class="form-group  text-center" id="btnRow">
            <button type="submit" class="btn btn-primary" id="submitButton">Εγγραφή</button>
          </div>
          <div class="form-group form-text" id="wrongInput">
            Παρακαλούμε ελέγξτε τα στοιχεία που έχετε εισάγει και προσπαθήστε ξανά!
          </div>
          <div class="form-group form-text" id="idTaken">
            Ουπς! Τα στοιχεία που δώσατε αντιστοιχούν σε ήδη υπάρχοντα λογαριασμό. Μήπως πρέπει να συνδεθείτε; Αν είστε
            σίγουροι
            ότι δεν έχετε ήδη εγγραφεί, παρακαλούμε προσπαθήστε πάλι.
          </div>
        </form>
        <div id="loginLink">
          Έχετε ήδη κάνει εγγραφή; Συνδεθείτε <a href="/login.html">εδώ</a>.
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
  <script src="/js/user-signup.js"></script>

</body>

</html>