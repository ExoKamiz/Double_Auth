<?php
include_once "classes/Page.php";
include_once "classes/Pdo_.php";
include_once "login.php";

Page::display_header("Main page");

$Pdo = new Pdo_();

// adding new user
if (isset($_REQUEST['add_user'])) {
    $login = $_REQUEST['login'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $password2 = $_REQUEST['password2'];
    if ($password == $password2) {
        $Pdo->add_user($login, $email, $password);
    } else {
        echo 'Passwords doesn\'t match';
    }
}

// change password
if (isset($_REQUEST['change_password'])) {
    $login = $_REQUEST['login'];
    $password = $_REQUEST['passwordCh'];
    $password2 = $_REQUEST['passwordCh2'];
    if ($password == $password2) {
        $Pdo->change_pass($login, $password);
    } else {
        echo 'Passwords doesn\'t match';
    }
}

//if (isset($_REQUEST['log_user_in'])) {
//    $code = $_REQUEST['code'];
//    $login = $_SESSION['login'];
//    if ($Pdo->log_2F_step2($login, $code)) {
//        echo 'You are logged in as: ' . $_SESSION['login'];
//        $_SESSION['logged'] = 'YES';
//    }
//}


?>
<?php
Page::display_sesion();
?>

<H2> Main page</H2>
<!---------------------------------------------------------------------->
<hr>
<P> Register new user</P>
<form method="post" action="index.php">
    <table>
        <tr>
            <td>login</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="login" id="login" size="40"/>
            </td>
        </tr>
        <tr>
            <td>email</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="email" id="email" size="40"/>
            </td>
        </tr>
        <tr>
            <td>password</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password" id="password" size="40"/>
            </td>
        </tr>
        <tr>
            <td>repeat password</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password2" id="password2" size="40"/>
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Create account" name="add_user">
</form>
<!---------------------------------------------------------------------->
<hr>
<P> Log in</P>
<form method="post" action="index.php">
    <table>
        <tr>
            <td>login</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="login" id="login" size="40" value="test123"/>
            </td>
        </tr>
        <tr>
            <td>password</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password"
                       id="password" size="40" value="student"/>
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Log in" name="log_user_in">
</form>
<?php
Page::display_navigation();
?>
</body>
</html>
