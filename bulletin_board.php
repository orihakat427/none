<?php
//DB登録処理
if (isset($_POST) && !empty($_POST['content'])) {
  $station = $_POST['station'];
  $comment = $_POST['comment'];
  $datetime = $_POST['datetime'];

// １．データベースに接続する
$dsn = 'mysql:dbname=gs_db;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8');

// ２．SQL文を実行する
$sql = "INSERT INTO `bulletin_board`(`station`,`comment`,`datetime`) VALUES (?, ?, now())";
  //プリペアドステートメント
$data = array($station,$comment,$datetime);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

//データ取得(fetch処理でデータを取得)
$survey_line = array();
while(1){
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  if($rec == false){
    break;
  }
  $survey_line[] = $rec;
}

// ３．データベースを切断する
$dbh = null;

}
 ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>東北本線遅延状況</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-train"></i> 東北本線遅延状況</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bulletin_board.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="station" class="form-control" id="validate-text" placeholder="駅名（上り・下り）" required>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="状況" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>シェアする</button>
        </form>
      </div>

      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">
          <article class="timeline-entry">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon bg-success">
                      <i class="entypo-feather"></i>
                      <i class="fa fa-exclamation-triangle"></i>
                  </div>
                  <div class="timeline-label label-sita">
                      <h2><a href="#">例）福島駅上り</a> <span>2018-04-27</span></h2>
                      <p>例）15分遅れです</p>
                  </div>
<?php
  //コンテンツからデータベースを呼び出す。
  $dsn = 'mysql:dbname=gs_db; hostlocalhost';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->query('SET NAMES utf8');

  //SQL実行
  $sql = "SELECT * FROM `bulletin_board` ORDER BY `created` DESC";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  //データ取得
  while(1){
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rec == false){
      break;
    }
    $_station = $rec['station'];
    $_comment = $rec['comment'];
    $_datetime = $rec['datetime'];

    $box = [];
    $box = "
            <div class='timeline-label label-sita'>
             <h2><a href='#'>$_station</a>
             <span>$_datetime</span></h2>
             <p>$_comment</p>
            </div>";

    echo $box;
  }

  //3. データベース切断
    $dbh = null;

 ?>

              </div>
          </article>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>