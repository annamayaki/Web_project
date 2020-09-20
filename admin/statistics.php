<?php

require_once __DIR__.'\\..\\php\\auth-check.php';
authCheck("admin");

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Στατιστικά | SuperTrouper</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&subset=greek">
  <link rel="stylesheet" href="/css/admin-style.css">
  <link rel="stylesheet" href="/css/admin-statistics.css">

</head>

<body>

  <div class="container-fluid">
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
        <div class="navbar-brand">SuperTrouper.co</div>
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
        <div class="row justify-content-lg-start">
          <div id="page-header">Στατιστικά Στοιχεία Εγγραφών των Χρηστών</div>
        </div>
        <div class="row justify-content-lg-start" id="stat-descr">
          <div>Στη σελίδα αυτή μπορείτε να εξετάσετε συγκεντρωτικά την κατανομή
            των εγγραφών όλων των χρηστών στη βάση δεδομένων μας σύμφωνα με έξι διαφορετικά κριτήρια.
          </div>
        </div>
        <div class="row justify-content-lg-start chart-header" id="activity">
          <div>Κατανομή Εγγραφών ανά Δραστηριότητα</div>
        </div>
        <div class="row justify-content-lg-start chart-container">
          <div class="chart-div" id="activity-chart"></div>
        </div>
        <div class="row justify-content-lg-start chart-header" id="user">
          <div>Κατανομή Εγγραφών ανά Χρήστη</div>
        </div>
        <div class="row justify-content-lg-start chart-container">
          <div class="chart-div" id="user-chart"></div>
        </div>
        <div class="row justify-content-lg-start chart-header" id="year">
          <div>Κατανομή Εγγραφών ανά Έτος</div>
        </div>
        <div class="row justify-content-lg-start chart-container">
          <div class="chart-div" id="year-chart"></div>
        </div>
        <div class="row justify-content-lg-start chart-header" id="month">
          <div>Κατανομή Εγγραφών ανά Μήνα</div>
        </div>
        <div class="row justify-content-lg-start chart-container">
          <div class="chart-div" id="month-chart"></div>
        </div>
        <div class="row justify-content-lg-start chart-header" id="dow">
          <div>Κατανομή Εγγραφών ανά Ημέρα της Εβδομάδας</div>
        </div>
        <div class="row justify-content-lg-start chart-container">
          <div class="chart-div" id="dow-chart"></div>
        </div>
        <div class="row justify-content-lg-start chart-header" id="hour">
          <div>Κατανομή Εγγραφών ανά Ώρα</div>
        </div>
        <div class="row justify-content-lg-start chart-container">
          <div class="chart-div" id="hour-chart"></div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="/js/admin-statistics.js"></script>

</body>

</html>