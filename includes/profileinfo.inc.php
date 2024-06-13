<?php
error_reporting(E_ALL);
session_start();

include "../classes/dbh.classes.php";
include "../classes/profileinfo.classes.php";
include "../classes/profileinfo-contr.classes.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION["user_id"];
    $username = $_SESSION["username"];
    $user_type = $_SESSION["user_type"];
    $full_name = htmlspecialchars($_POST["full_name"], ENT_QUOTES, "UTF-8");
    $profession = htmlspecialchars($_POST["profession"], ENT_QUOTES, "UTF-8");
    $firm = htmlspecialchars($_POST["firm"], ENT_QUOTES, "UTF-8");
    $specializations = isset($_POST['law_specializations']) ? $_POST['law_specializations'] : [];
    $experience_years = htmlspecialchars($_POST["experience_years"], ENT_QUOTES, "UTF-8");
    $phone_number = htmlspecialchars($_POST["phone_number"], ENT_QUOTES, "UTF-8");
    $working_hours_start = htmlspecialchars($_POST["working_hours_start"], ENT_QUOTES, "UTF-8");
    $working_hours_end = htmlspecialchars($_POST["working_hours_end"], ENT_QUOTES, "UTF-8");
    $physical_address = htmlspecialchars($_POST["physical_address"], ENT_QUOTES, "UTF-8");
    $profile_about = htmlspecialchars($_POST["profile_about"], ENT_QUOTES, "UTF-8");

    // Convert the specializations array to a JSON string
    $specializationsJson = json_encode($specializations);

    $profileInfo = new ProfileInfoContr($user_id, $username, $user_type);

    $profileInfo->updateProfileInfo($full_name, $profession, $firm, $specializationsJson, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about);


    header("location: ../pract.dash.php?error=none");
}