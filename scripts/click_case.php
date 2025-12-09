<?php
include('./sql-connect.php');

if (isset($_POST["cell"])) {
  $sql = new SqlConnect();

  $query = '
    UPDATE joueur1
    SET checked = CASE WHEN checked = 0 THEN 1 ELSE 0 END
    WHERE id = :cell;
  ';

  $req = $sql->db->prepare($query);
  $req->execute(['cell' => $_POST["cell"]]);

  header("Location: ../index.php");

  exit;
}