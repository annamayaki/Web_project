<?php

require_once __DIR__.'\\..\\php\\auth-check.php';
authCheck("user");

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8"/>

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Ανάλυση Δεδομένων | SuperTrouper</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&subset=greek">
  <link rel="stylesheet" href="/css/user-style.css">
  <link rel="stylesheet" href="/css/user-locations.css">

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
      <div class="row">
        <aside>
          <!-- Sidebar (JQuery -> load) -->
        </aside>

        <main class="pre-scrollable">
          <h2 id="page-header">Ανάλυση των Δεδομένων Τοποθεσίας σας</h2>
            <div class="row">
              <div id="map"></div>
            </div>
            <div class="row charts">
              <h4>Κατανομή Εγγραφών Δραστηριοτήτων</h4>
            </div>
            <div class="row justify-content-sm-center charts">
              <div id="activity-chart"></div>
            </div>
            <div class="row charts">
              <h4>Δραστηριότητες στις Ημέρες</h4>
            </div>
            <div class="row justify-content-sm-center charts">
              <div id="dow-table-div"></div>
            </div>
            <div class="row charts">
              <h4>Δραστηριότητες στις Ώρες</h4>
            </div>
            <div class="row justify-content-sm-center charts">
              <div id="hour-table-div"></div>
            </div>
            <div class="row" id="invalid-form">
              <p>Παρακαλούμε συμπληρώστε όλα τα πεδία!</p>
            </div>
              <form id="refine-opts">
                <div class="row justify-content-sm-center">
                  <div class="col-sm-4">
                    <label for="startYear" class="yearLabels">Από</label>
                    <select class="custom-select form-group" id="startYear" required>
                      <option value="" disabled selected>Έτος</option>
                      <option value="all">Όλα</option>
                      <option value="2015">2015</option>
                      <option value="2016">2016</option>
                      <option value="2017">2017</option>
                      <option value="2018">2018</option>
                      <option value="2019">2019</option>
                      <option value="2020">2020</option>
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label for="endYear" class="yearLabels">Μέχρι </label>
                    <select class="custom-select form-group" id="endYear">
                      <option value="" disabled selected>Έτος</option>
                      <option value="2015">2015</option>
                      <option value="2016">2016</option>
                      <option value="2017">2017</option>
                      <option value="2018">2018</option>
                      <option value="2019">2019</option>
                      <option value="2020">2020</option>
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-check form-group">
                      <input class="form-check-input checkCustom" type="checkbox" id="yearRange" value="range">
                      <label class="form-check-label labelCustom" for="yearRange">Εύρος</label>
                    </div>
                  </div>
                </div>
                <div class="row justify-content-sm-center">
                  <div class="col-sm-4">
                    <label for="startMonth" class="monthLabels">Από </label>
                    <select class="custom-select form-group" id="startMonth" required>
                      <option value="" disabled selected>Μήνας</option>
                      <option value="all">Όλοι</option>
                      <option value="1">Ιανουάριος</option>
                      <option value="2">Φεβρουάριος</option>
                      <option value="3">Μάρτιος</option>
                      <option value="4">Απρίλιος</option>
                      <option value="5">Μάιος</option>
                      <option value="6">Ιούνιος</option>
                      <option value="7">Ιούλιος</option>
                      <option value="8">Αύγουστος</option>
                      <option value="9">Σεπτέμβριος</option>
                      <option value="10">Οκτώβριος</option>
                      <option value="11">Νοέμβριος</option>
                      <option value="12">Δεκέμβριος</option>
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label for="endMonth" class="monthLabels">Μέχρι</label>
                    <select class="custom-select form-group" id="endMonth">
                      <option value="" disabled selected>Μήνας</option>
                      <option value="1">Ιανουάριος</option>
                      <option value="2">Φεβρουάριος</option>
                      <option value="3">Μάρτιος</option>
                      <option value="4">Απρίλιος</option>
                      <option value="5">Μάιος</option>
                      <option value="6">Ιούνιος</option>
                      <option value="7">Ιούλιος</option>
                      <option value="8">Αύγουστος</option>
                      <option value="9">Σεπτέμβριος</option>
                      <option value="10">Οκτώβριος</option>
                      <option value="11">Νοέμβριος</option>
                      <option value="12">Δεκέμβριος</option>
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-check form-group">
                      <input class="form-check-input checkCustom" type="checkbox" id="monthRange" value="range">
                      <label class="form-check-label labelCustom" for="monthRange">Εύρος</label>
                    </div>
                  </div>
                </div>
                <div class="row justify-content-sm-center" id="btnRow">
                  <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary" id="submit">Εφαρμογή Κριτηρίων</button>
                  </div>
                </div>
              </form>
            <div class="overlay"></div>
        </main>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>
    <script src="/lib/leaflet.heat.min.js"></script>
    <script src="/js/user-locations.js"></script>

  </body>
</html>
