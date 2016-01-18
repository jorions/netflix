<!DOCTYPE html>
<html>
<head>
    <title>Netflix Search</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>
<?php

include 'netflix.php';

session_start();

if(!isset($_POST['submit'])) {
    $_SESSION['title'] = '';
    $_SESSION['director'] = '';
    $_SESSION['actor'] = '';
} else {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['director'] = $_POST['director'];
    $_SESSION['actor'] = $_POST['actor'];
}

?>

<div class='top'>
    <img src='images/logo.jpg' />
    <form action="<?php echo ($_SERVER['PHP_SELF']);?>" method="POST">
        <input class="wide" type="text" name="title" value="<?php echo $_SESSION['title'];?>" placeholder="Title" />
        <input class="wide" type="text" name="director" value="<?php echo $_SESSION['director'];?>" placeholder="Director" />
        <input class="wide" type="text" name="actor" value="<?php echo $_SESSION['actor'];?>" placeholder="Actor" />
        <br />
        <input class="wide" type="submit" name="submit" value="Search!" />
    </form>
    <div class="note">Please note using multiple search criteria at once can confuse the API</div>
</div>


<?php

// If director is not a string then tell the user that is the case
if(isset($_POST['submit']) && $_SESSION['director'] != '' && !is_string($_SESSION['director'])) {
    exit('<div class="error">Director names must be strings</div>');
}

// If actor is not a string then tell the user that is the case
if(isset($_POST['submit']) && $_SESSION['actor'] != '' && !is_string($_SESSION['actor'])) {
    exit('<div class="error">Actor names must be strings</div>');
}

// If nothing is entered tell the user
if(isset($_POST['submit']) && $_SESSION['title'] == '' && $_SESSION['director'] == '' && $_SESSION['actor']  == '') {
    exit('<div class="error">Enter something to search in at least one field</div>');
}

// With validation complete set each of the parameters and submit a search query
$search = new NetflixSearch();

if($_SESSION['title'] != '') {
    $search->setTitle($_SESSION['title']);
}
if($_SESSION['director'] != '') {
    $search->setDirector($_SESSION['director']);
}
if($_SESSION['actor'] != '') {
    $search->setActor($_SESSION['actor']);
}

// Use a try statement to make sure the search returns something. If it doesn't, catch exception and output array of suggested shows
try {

    echo $search->performSearch();

} catch (Exception $e) {

    echo '<div class="error">Nothing returned from that search! Maybe you could check out one of these awesome shows:</div>';

    // Array of shows
    $goodShows = array("30 Rock", "It's Always Sunny In Philadelphia", "Parks and Recreation", "The Inexplicable Universe With Neil deGrasse Tyson");

    $suggestion = new NetflixSearch();

    // Iterate through array and run search each time
    foreach ($goodShows as $show) {
        $suggestion->setTitle($show);
        echo $suggestion->performSearch();
    }
}