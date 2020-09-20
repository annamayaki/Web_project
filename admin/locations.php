<?php

require_once __DIR__.'\\..\\php\\auth-check.php';
authCheck("admin");

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Στοιχεία Τοποθεσίας | SuperTrouper</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&subset=greek">
  <link rel="stylesheet" href="/css/admin-style.css">
  <link rel="stylesheet" href="/css/admin-locations.css">

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
            πάλι σε μερικά δευτερόλεπτα.
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="deleteDbModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Διαγραφή Δεδομένων</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Είστε σίγουροι ότι επιθυμείτε τη διαγραφή όλων των δεδομένων από τη βάση;
            Αυτή η ενέργεια είναι μη αναστρέψιμη.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="confirmBtn">Ναι, την επιθυμώ</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Επιστροφή</button>
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
        <div class="row">
          <h2 id="page-header">Επισκόπηση Δεδομένων Τοποθεσίας Χρηστών</h2>
        </div>
        <div class="row">
          <div id="map"></div>
        </div>
        <div class="row" id="invalid-form">
          <p>Παρακαλούμε συμπληρώστε όλα τα πεδία!</p>
        </div>
        <form id="refine-opts">
          <div class="row justify-content-sm-center">
            <div class="col-sm-4">
              <label for="startYear" class="yearLabels">Από</label>
              <select class="custom-select form-group" id="startYear">
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
              <select class="custom-select form-group" id="startMonth">
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
          <div class="row justify-content-sm-center">
            <div class="col-sm-4">
              <label for="startDow" class="dowLabels">Από</label>
              <select class="custom-select form-group" id="startDow">
                <option value="" disabled selected>Ημέρα</option>
                <option value="all">Όλες</option>
                <option value="1">Δευτέρα</option>
                <option value="2">Τρίτη</option>
                <option value="3">Τετάρτη</option>
                <option value="4">Πέμπτη</option>
                <option value="5">Παρασκευή</option>
                <option value="6">Σάββατο</option>
                <option value="0">Κυριακή</option>
              </select>
            </div>
            <div class="col-sm-4">
              <label for="endDow" class="dowLabels">Μέχρι</label>
              <select class="custom-select form-group" id="endDow">
                <option value="" disabled selected>Ημέρα</option>
                <option value="1">Δευτέρα</option>
                <option value="2">Τρίτη</option>
                <option value="3">Τετάρτη</option>
                <option value="4">Πέμπτη</option>
                <option value="5">Παρασκευή</option>
                <option value="6">Σάββατο</option>
                <option value="0">Κυριακή</option>
              </select>
            </div>
            <div class="col-sm-4">
              <div class="form-check form-group">
                <input class="form-check-input checkCustom" type="checkbox" id="dowRange" value="range">
                <label class="form-check-label labelCustom" for="dowRange">Εύρος</label>
              </div>
            </div>
          </div>
          <div class="row justify-content-sm-center">
            <div class="col-sm-4">
              <label for="startHour" class="hourLabels">Από </label>
              <select class="custom-select form-group" id="startHour">
                <option value="" disabled selected>Ώρα</option>
                <option value="all">Όλες</option>
                <option value="0">00</option>
                <option value="1">01</option>
                <option value="2">02</option>
                <option value="3">03</option>
                <option value="4">04</option>
                <option value="5">05</option>
                <option value="6">06</option>
                <option value="7">07</option>
                <option value="8">08</option>
                <option value="9">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="21">22</option>
                <option value="23">23</option>
              </select>
            </div>
            <div class="col-sm-4">
              <label for="endHour" class="hourLabels">Μέχρι</label>
              <select class="custom-select form-group" id="endHour">
                <option value="" disabled selected>Ώρα</option>
                <option value="0">00</option>
                <option value="1">01</option>
                <option value="2">02</option>
                <option value="3">03</option>
                <option value="4">04</option>
                <option value="5">05</option>
                <option value="6">06</option>
                <option value="7">07</option>
                <option value="8">08</option>
                <option value="9">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="21">22</option>
                <option value="23">23</option>
              </select>
            </div>
            <div class="col-sm-4">
              <div class="form-check form-group">
                <input class="form-check-input checkCustom" type="checkbox" id="hourRange" value="range">
                <label class="form-check-label labelCustom" for="hourRange">Εύρος</label>
              </div>
            </div>
          </div>
          <div class="row justify-content-sm-center" id="rogueRow">
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input" type="checkbox" id="activAll" value="all">
              <label class="form-check-label" for="activAll">Όλες οι Δραστηριότητες</label>
            </div>
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input indivActBoxes" type="checkbox" id="vehicle" value="IN_VEHICLE">
              <label class="form-check-label" for="vehicle">IN_VEHICLE</label>
            </div>
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input indivActBoxes" type="checkbox" id="bicycle" value="ON_BICYCLE">
              <label class="form-check-label" for="bicycle">ON_BICYCLE</label>
            </div>
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input indivActBoxes" type="checkbox" id="foot" value="ON_FOOT">
              <label class="form-check-label" for="foot">ON_FOOT</label>
            </div>
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input indivActBoxes" type="checkbox" id="running" value="RUNNING">
              <label class="form-check-label" for="running">RUNNING</label>
            </div>
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input indivActBoxes" type="checkbox" id="still" value="STILL">
              <label class="form-check-label" for="still">STILL</label>
            </div>
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input indivActBoxes" type="checkbox" id="tilting" value="TILTING">
              <label class="form-check-label" for="tilting">TILTING</label>
            </div>
            <div class="form-check form-check-inline form-group">
              <input class="form-check-input indivActBoxes" type="checkbox" id="walking" value="WALKING">
              <label class="form-check-label" for="walking">WALKING</label>
            </div>
          </div>
          <div class="row justify-content-sm-center" id="btnRow">
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary" id="submit">Εφαρμογή Κριτηρίων</button>
            </div>
            <div class="col-sm-3">
              <input type="button" class="btn btn-success" id="exportJSON" value="Εξαγωγή σε JSON">
            </div>
            <div class="col-sm-3">
              <input type="button" class="btn btn-danger" id="clearDB" value="Διαγραφή Δεδομένων">
            </div>
          </div>
        </form>
      </main>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>
  <script src="/lib/leaflet.heat.min.js"></script>
  <script src="/js/admin-locations.js"></script>

</body>

</html>