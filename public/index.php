<?php 
  require_once __DIR__ . '/../bootstrap.php';

  $workingDaysTab = new WorkingDays();
?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="/components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="page-header"><?php echo $workingDaysTab->getHeader(); ?></h1>
          <div>
            <h2 id="calendar-month"><?php echo $workingDaysTab->getCurrentMonth(); ?>
              <span class="calendar-days"><?php echo $workingDaysTab->getSubHeader(); ?></span>
            </h2>
          </div>
          <div>
            <?php
            echo $workingDaysTab->getMonthPicker();
            ?>
          </div>
          <div>
            <?php
            echo $workingDaysTab->getContent();
            ?>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="/components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/components/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="/components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/js/script.js"></script>
  </body>
</html>
