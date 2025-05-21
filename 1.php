<?php

date_default_timezone_set("Asia/Tokyo");

$comment_array = array();
$error=true;

try{
$pdo = new PDO('mysql:host=localhost;dbname=kaeizaban', "root","");
}catch(PDOException $e){
    echo $e->getMessage();
} 

if(!empty($_POST["submitbutton"])){
    

    if(empty(trim($_POST["username"]))){
        echo "名前を書いてください\n";
        $error = false;
    }
    if(empty(trim($_POST["comment"]))){
        echo "コメントを書いてください";
        $error = false;
    }
}


if($error){
    $postdate = date("Y-m-d H:i:s");

    try{
   $stmt = $pdo->prepare("INSERT INTO `keiziban-table` (`username`,`comment`,`postdate`) VALUES(:username,:comment,:postdate)");
   $stmt->bindParam(':username', $_POST["username"], PDO::PARAM_STR);
   $stmt->bindParam(':comment', $_POST["comment"],PDO::PARAM_STR);
   $stmt->bindParam(':postdate', $postdate ,PDO::PARAM_STR);
   $stmt->execute();

   header("Location: " . $_SERVER["PHP_SELF"]);
        exit;

    }catch(PDOException $e){
    
} 
}


//データベースより取得
$sql="SELECT * FROM `keiziban-table` ";
$comment_array = $pdo->query($sql);

//接続を閉じる
$pdo = null;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="title">掲示板</h1>
    <p class="p">好きなことを書いてください</p>

 <form class="form" method="POST">
                        <label for="">名前:</label>
            <input type="text" name="username">
            <input type="submit" value="書き込む" name="submitbutton" >
            <div>
                <textarea class="commentText" name="comment">

        
                </textarea>
            </div>

        </form>

    <div class="board">
        <?php foreach($comment_array as $comment):?>
            <div class="comment-item">
                <p class="username">名前：<span class="username1"><?php echo $comment["username"] ?></span></p>
                <p class="comment"><?php   echo $comment["comment"]?></p>
                <time><?php   echo $comment["postdate"]?></time>
            </div>
            <?php endforeach;?>

       
    </div>

    



    </div>
    
</body>
</html>
