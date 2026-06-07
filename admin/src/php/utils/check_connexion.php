<?php
if(!isset($_SESSION['admin'])){
    header("location: index_.php?page=login.php");
    exit();
}