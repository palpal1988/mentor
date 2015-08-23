<?php
/**
 * Created by PhpStorm.
 * User: photoruction
 * Date: 15/07/16
 * Time: 0:01
 */

include("common.php");

//データベースに接続
try {
    $pdo = new PDO('mysql:dbname=' . DBNAME . '; host=' . HOST, USER, PASS);
} catch (PDOException $e) {
    exit('データベース失敗' . $e->getMessage());
}

$stmt = $pdo->query('SET NAMES utf8');

if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    //元のファイルネーム
    $fileName = $_FILES['file']['name'];
    //一意のファイルネーム
    $uqFileName = uniqid('p') . '.' . getExt($fileName);
    $tmpName = $_FILES['file']['tmp_name'];

    if (move_uploaded_file($tmpName, "photo/" . $uqFileName)) {
        chmod("photo/" . $uqFileName, 0644);
        $stmt = $pdo->prepare("INSERT INTO photo (p_id, p_fileName, p_date ,p_resistDate,p_photographer,p_starFlg,p_blackBoardFlg )VALUES(NULL, :p_fileName, sysdate(), sysdate(),:p_photographer,0,0)");
        $stmt->bindValue(':p_fileName', $uqFileName);
        $stmt->bindValue(':p_photographer', '中島貴春');
        $status = $stmt->execute();

        if ($status == false) {

            echo "SQLエラー";
            exit;

        }
        createThumbnail($uqFileName);
    }

    echo 'アップロードしました';

} else {

    echo "アップロードに失敗しました";

}



?>