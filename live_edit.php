<?php
    include("auth.html");
	$array = file('C:\Program Files\Ampps\mysqlpassword.txt');
    $con = mysqli_connect("localhost","root","$array[0]");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $week = $_POST['e'];
    $username = $_SESSION['username'];
    $row = $_POST['a'];
    $column = $_POST['b'];
    $data = $_POST['c'];
    $type = $_POST['d'];

    if ($type === 'change'){
        $sql = "UPDATE `$username`.`$week` SET `$column`='$data' WHERE id=$row";
    } else if ($type === 'add') {
        $sql = "INSERT INTO `$username`.`$week` (id, class, time, monday, tuesday, wednesday, thursday, friday, notes, s1, s2, s3, s4, s5, s6, s7, s8, l1, l2, l3, l4, l5, l6, l7, l8) VALUES (NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
        $con->query("ALTER TABLE `$username`.`$week` DROP `id`");
        $con->query("ALTER TABLE `$username`.`$week` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (`id`)");
    } else if ($type === 'remove') {
        $sql = "DELETE FROM `$username`.`$week` WHERE id=$row";
        $con->query("ALTER TABLE `$username`.`$week` DROP `id`");
        $con->query("ALTER TABLE `$username`.`$week` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (`id`)");
    } else if ($type === 'up') {
        $row1 = $row - 1;
        $con->query("UPDATE `$username`.`$week` SET id='-1' WHERE id=$row");
        $con->query("UPDATE `$username`.`$week` SET id='$row' WHERE id=$row1");
        $con->query("UPDATE `$username`.`$week` SET id='$row1' WHERE id=(-1)");
    } else if ($type === 'down') {
        $row1 = $row + 1;
        $con->query("UPDATE `$username`.`$week` SET id='-1' WHERE id=$row");
        $con->query("UPDATE `$username`.`$week` SET id='$row' WHERE id=$row1");
        $con->query("UPDATE `$username`.`$week` SET id='$row1' WHERE id=(-1)");
    } else if ($type === 'style') {
        $scolumn = 's'.$column;
        $sql = "UPDATE `$username`.`$week` SET `$scolumn`='$data' WHERE id=$row";
    } else if ($type === 'link'){
        $sql = "UPDATE `$username`.`$week` SET `$column`=CONCAT_WS('\n', `$column`,'$data') WHERE id=$row";
    } else if ($type === 'linkdelete'){
        $sql = "UPDATE `$username`.`$week` SET `$column`=REPLACE(`$column`, '\n$data', '') WHERE id=$row";
    }
    mysqli_query($con, $sql);
?>
