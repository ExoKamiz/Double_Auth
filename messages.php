<?php
include_once "classes/Page.php";
include_once "classes/Db.php";
Page::display_header("Messages");
$db = new Db("localhost", "root", "", "vy_lab4_cyber");
// adding new message
if (isset($_REQUEST['add_message'])) {
    $name = $_REQUEST['name'];
    $type = $_REQUEST['type'];
    $content = $_REQUEST['content'];
    if (!$db->addMessage($name, $type, $content))
        echo "Adding new message failed";
}

// adding new message
//    if (isset($_REQUEST['add_message'])) {
//        $name = strip_tags($_REQUEST['name']);
//        $type = $_REQUEST['type'];
//        $content = strip_tags($_REQUEST['content']);
//        if (!$db->addMessage($name,$type,$content))
//            echo "Adding new message failed";
//    }
?>
<?php
Page::display_sesion();
?>
<!---------------------------------------------------------------------->
<hr>
<P> Messages</P>
<ol>
    <?php
    $where_clause = "";
    // filtering messages
    if (isset($_REQUEST['filter_messages'])) {
        $string = $_REQUEST['string'];
        $where_clause = " WHERE name LIKE '%" . $string . "%'";
    }
    $sql = "SELECT * from message" . $where_clause;
    echo $sql;
    echo "<BR/><BR/>";
    $messages = $db->select($sql);
    foreach ($messages as $msg)://returned as objects
        echo "<li>";
        echo $msg->message;
        echo "</li>";
    endforeach;
    ?>
</ol>
<!---------------------------------------------------------------------->
<hr>
<P>Messages filtering</P>
<form method="post" action="messages.php">
    <table>
        <tr>
            <td>Title contains:</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="string" id="string" size="80"/>
            </td>
        </tr>
    </table>
    <input type="submit" id="submit"
           value="Find messages" name="filter_messages">
</form>
<!---------------------------------------------------------------------->
<hr>
<P>Navigation</P>
<?php
Page::display_navigation();
?>
</body>
</html>