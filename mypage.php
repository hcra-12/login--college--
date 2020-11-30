<?php
mb_internal_encoding("utf8");
session_start();

if(empty($_SESSION['id'])){

  try{
    //try catch文。DBに接続できなければエラーメッセージを表示
    $pdo = new PDO("mysql:dbname=lesson01;host=localhost;","root","");
  }catch(PDOException $e){
    die("<p>申し訳ございません。現在サーバーが込み合っており一時的にアクセスが出来ません。<br>しばらくしてから再度ログインをしてください</p>
    <a href = 'http:localhost/login_mypage/login.php'>ログイン画面へ</a>"
    );
  }
  $stmt = $pdo->prepare("select * from login_mypage where mail = ? && password = ?");

  $stmt->bindValue(1,$_POST["mail"]);
  $stmt->bindValue(2,$_POST["password"]);

  $stmt->execute();
  $pdo = NULL;

  while($row = $stmt->fetch()){
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['picture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
  }
  if(empty($_SESSION['id'])){
    header("Location:login_error.php");
  }
  if(!empty($_POST['login_keep'])){
    $_SESSION['login_keep']=$_POST['login_keep'];
  }
}
if(!empty($_SESSION['id']) && !empty($_SESSION['login_keep'])){
  setcookie('mail',$_SESSION['mail'],time()+60*60*24*7);
  setcookie('password',$_SESSION['password'],time()+60*60*24*7);
  setcookie('login_keep',$_SESSION['login_keep'],time()+60*60*24*7);
}else if(empty($_SESSION['login_keep'])){
  setcookie('mail','',time()-1);
  setcookie('password','',time()-1);
  setcookie('login_keep','',time()-1);
}

?>
<!DOCTYPE HTML>
<html lang = "ja">
 <head>
   <title>マイページ登録</title>
   <link rel = "stylesheet" type = "text/css" href = "mypage.css">
 </head>
   <body>
     <header>
       <img src = "4eachblog_logo.jpg">
       <a href=log_out.php class = "log_out">ログアウト</a>
     </header>

     <main>
       <h2>会員情報</h2>
       <?php echo "こんにちは！　".$_SESSION['name']."　さん"; ?><br>
       <div class = "img-pass">
         <img src = "<?php echo $_SESSION['picture']; ?>" class = "imageC">
         <div class = "name-pass">
           <p>氏名：<?php echo $_SESSION['name']; ?></p>
           <p>メール：<?php echo $_SESSION['mail']; ?></p>
           <p>パスワード：<?php echo $_SESSION['password']; ?></p>
         </div>
       </div>
       <?php echo $_SESSION['comments']; ?>

       <div class = "button">
         <form action = "mypage_hensyu.php" method = "post">
           <input type = "hidden" value = "<?php echo rand(1,10); ?>" name = "from_mypage">
           <input type = "submit" value = "編集する" class = "submit_button">
        </form>
      </div>

    </main>
    <footer>
      ©2018 InterNous.inc All rights reserved
    </footer>
  </body>
</html>
