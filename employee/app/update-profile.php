<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$fname = ucwords(trim($_POST['fname']));
$lname = ucwords(trim($_POST['lname']));
$mydate = $_POST['date'];
$mymonth = $_POST['month'];
$myyear = $_POST['year'];
$myemail = trim($_POST['email']);
$edu = ucwords(trim($_POST['education']));
$title = ucwords(trim($_POST['title']));
$gender = $_POST['gender'];
$city = ucwords(trim($_POST['city']));
$street = ucwords(trim($_POST['street']));
$zip = ucwords(trim($_POST['zip']));
$about = $_POST['about'];
$phone = trim($_POST['phone']);
$country = $_POST['country'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT email FROM tbl_users WHERE email = :email AND member_no != :myid LIMIT 1");
    $stmt->bindParam(':email', $myemail);
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $rec = count($result);

    if ($rec == 0) {
        $stmt = $conn->prepare("UPDATE tbl_users SET first_name = :fname, last_name = :lname, gender = :gender, bdate = :bdate, bmonth = :bmonth, byear = :byear, email = :email, education = :education, title = :title, city = :city, street = :street, zip = :zip, country = :country, phone = :phone, about = :about WHERE member_no = :myid");
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':bdate', $mydate);
        $stmt->bindParam(':bmonth', $mymonth);
        $stmt->bindParam(':byear', $myyear);
        $stmt->bindParam(':email', $myemail);
        $stmt->bindParam(':education', $edu);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':zip', $zip);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':about', $about);
        $stmt->bindParam(':myid', $myid);
        $stmt->execute();

        $_SESSION['myfname'] = $fname;
        $_SESSION['mylname'] = $lname;
        $_SESSION['myemail'] = $myemail;
        $_SESSION['mydate'] = $mydate;
        $_SESSION['mymonth'] = $mymonth;
        $_SESSION['myyear'] = $myyear;
        $_SESSION['myphone'] = $phone;
        $_SESSION['myedu'] = $edu;
        $_SESSION['mytitle'] = $title;
        $_SESSION['mycity'] = $city;
        $_SESSION['mystreet'] = $street;
        $_SESSION['myzip'] = $zip;
        $_SESSION['mycountry'] = $country;
        $_SESSION['mydesc'] = $about;
        $_SESSION['gender'] = $gender;
        header("location:../?r=9837");
        exit();
    } else {
        header("location:../?r=0927");
        exit();
    }
} catch (PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../?r=error");
    exit();
}
?>
