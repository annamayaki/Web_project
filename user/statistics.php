<?php

require_once __DIR__.'\\..\\php\\auth-check.php';
authCheck("user");

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <title>Επισκόπηση Στατιστικών | SuperTrouper</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&subset=greek">
  <link rel="stylesheet" href="/css/user-style.css">
  <link rel="stylesheet" href="/css/user-statistics.css">

</head>

<body>

  <div class="container-fluid" id="content">
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
            να φορτώσετε τη σελίδα και πάλι σε μερικά δευτερόλεπτα.
          </div>
        </div>
      </div>
    </div>
    <div class="row" id="navbar-row">
      <nav class="navbar">
        <h2 class="navbar-brand">SuperTrouper.co</h2>
        <button type="button" id="sidebarCollapse" class="btn btn-info btn-sm">
          <i class="material-icons" id="sidebarCollapseIcon">menu</i>
        </button>
      </nav>
    </div>
    <div>
      <aside>
        <!-- Sidebar (JQuery -> load) -->
      </aside>

      <main class="pre-scrollable">
        <div class="row justify-content-sm-start">
          <div id="page-header">Στατιστικά Στοιχεία των Εγγραφών σας</div>
        </div>
        <div class="row justify-content-sm-center">
          <div class="stat-descr">
            Στο γράφημα που ακολουθεί μπορείτε να εξετάσετε το σκορ των οικολογικών σας
            μετακινήσεων και τις μεταβολές του για τους τελευταίους 12 μήνες. Οικολογικές
            θεωρούνται οι μετακινήσεις μέσω δραστηριότητας σώματος, όπως η ποδηλασία. Ο τύπος
            μετακίνησης ανιχνεύεται μαζί με τα υπόλοιπα δεδομένα τοποθεσίας από λογισμικό της 
            υπηρεσίας <a id="gMapsLink" href="https://www.google.com/maps/" target="_blank">
            <u>Google Maps</u></a>.
          </div>
        </div>
        <div class="row justify-content-sm-center">
          <div id="eco-score-chart"></div>
        </div>
        <div class="row justify-content-sm-center">
          <div class="stat-descr">
            Ακολουθεί η κατάταξη (top-3) των χρηστών με τα υψηλότερα σκορ οικολογικής
            μετακίνησης για τον τελευταίο μήνα. <span id="pers-rank-msg"></span>
          </div>
        </div>
        <div class="row justify-content-sm-center">
          <div id="eco-score-rankings"></div>
        </div>
        <div class="row justify-content-sm-center">
          <div class="stat-descr">
            Τα δεδομένα που έχετε μεταφορτώσει στο αποθετήριό μας κυμαίνονται από
            <span id="start-date"></span> έως <span id="end-date"></span>. Η πιο
            πρόσφατη μεταφόρτωση έγινε την <span id="last-upload-date"></span>.
          </div>
        </div>
        <div class="overlay"></div>
      </main>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="/js/user-statistics.js"></script>

</body>

</html>