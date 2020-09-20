<?php

require_once __DIR__.'\\..\\php\\auth-check.php';
authCheck("user");

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <title>Μεταφόρτωση Δεδομένων | SuperTrouper</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&subset=greek">
  <link rel="stylesheet" href="/css/user-style.css">
  <link rel="stylesheet" href="/css/user-upload.css">

</head>

<body>

  <div class="container-fluid" id="content">
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Αποτέλεσμα Μεταφόρτωσης</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Τα δεδομένα σας μεταφορτώθηκαν και αποθηκεύθηκαν επιτυχώς!
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="failureModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Αποτέλεσμα Μεταφόρτωσης</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Υπήρξε ένα πρόβλημα κατά τη μεταφόρτωση των δεδομένων. Παρακαλούμε προσπαθήστε ξανά.
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
        <div class="row justify-content-sm-start">
          <div id="page-header">Μεταφόρτωση Αρχείου Δεδομένων Τοποθεσίας</div>
        </div>
        <div class="row justify-content-sm-center">
          <!-- <div id="map"></div> -->
          <img src="/img/fakeMap.png" alt="A map centered on Patras, GR">
        </div>
        <form>
          <div class="row form-group">
            <div id="dataLabel" class="form-text justify-content-sm-start">
              Παρακαλούμε επιλέξτε ή σύρετε και εναποθέστε 
              ένα συμπιεσμένο αρχείο JSON που να περιέχει τα δεδομένα τοποθεσίας σας:
            </div>
          </div>
          <div class="row form-group justify-content-sm-center">
            <input type="file" class="form-control-file" id="file" aria-describedby="dataInfo dataLabel">
            <div id="dataInfo" class="form-text text-muted">Δεδομένα που δεν αφορούν την πόλη της Πάτρας
              δεν θα εισάγονται στο σύστημα. Επιπλέον, για την προστασία της ιδιωτικότητάς σας, έχετε τη
              δυνατότητα να περιορίσετε τα δεδομένα τοποθεσίας πριν το ανέβασμα του αρχείου, σχηματίζοντας 
              ένα πολύγωνο πάνω στον χάρτη.
            </div>
          </div>
        </form>
          <div class="row justify-content-sm-center">
            <div>
              <button class="btn btn-primary" id="instrBtn" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <span id="showInstr">Εμφάνιση Οδηγιών</span>
                <span id="hideInstr">Απόκρυψη Οδηγιών</span>
              </button>
            </div>
            <div class="collapse" id="collapseExample">
              <div class="card card-body">
                <ol>
                  <li>
                    Μεταβείτε στη σελίδα της υπηρεσίας 
                    <a id="gTakeoutLink" href="https://takeout.google.com/" target="_blank">
                      <u>Google Takeout</u>
                    </a>.
                  </li>
                  <li>
                    Εισάγετε τα στοιχεία του Google Λογαριασμού σας. 
                  </li>
                  <li>
                    Στη σελίδα που θα εμφανιστεί, φροντίστε να μαρκάρετε την επιλογή 
                    "Ιστορικό τοποθεσίας". Πατώντας το κουμπί "Πολλές επιλογές" που 
                    της αντιστοιχεί, επιλέξτε ο τύπος αρχείου για το "Ιστορικό τοποθεσίας"
                    να είναι "JSON".
                  </li>
                  <li>
                    Προχωρήστε μέχρι το τέλος της σελίδας και κάντε κλικ στο "Επόμενο βήμα".
                  </li>
                  <li>
                    Στην επόμενη σελίδα αφήστε τις ρυθμίσεις ως έχουν και επιλέξτε "Δημιουργία 
                    εξαγωγής" και επιβεβαιώστε ξανά κάνοντας κλικ στο κουμπί "Δημιουργία 
                    νέας εξαγωγής".
                  </li>
                  <li>
                    Επιλέξτε "Λήψη" στη νέα σελίδα που θα εμφανιστεί και αποθηκεύστε το 
                    συμπιεσμένο (.zip) αρχείο.
                  </li>
                  <li>
                    Εντοπίστε το αρχείο "Ιστορικό τοποθεσίας.json" μέσα στο συμπιεσμένο
                    αρχείο και επιλέξτε το για μεταφόρτωση στην ιστοσελίδα μας.
                  </li>
                </ol>
              </div>
            </div>
          </div>
        <div class="overlay"></div>
      </main>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/3.8.4/dropzone.min.js"></script>
  <script src="/lib/prettysize.js"></script>
  <script src="/lib/oboe-browser.min.js"></script>
  <script src="/js/user-upload.js"></script>

</body>

</html>
