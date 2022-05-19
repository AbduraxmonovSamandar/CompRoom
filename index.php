<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <title>Game club</title>
</head>
<body>
<script>
    // js script for display real time
    function checkStartTime(id, status, startTime, stopTime) {
        setTimeout(checkStartTime, 500, id, status, startTime, stopTime);
        var today = new Date();
        var time = Math.floor(today.getTime() / 1000) - startTime;
        var outTime = stopTime - Math.floor(today.getTime() / 1000);
        var h = Math.floor(time / 60 / 60);
        var m = Math.floor(time / 60) - h * 60;
        var s = Math.floor(time) - h * 60 * 60 - m * 60;
        h = checkTime(h)
        m = checkTime(m);
        s = checkTime(s);

        var qh = Math.floor(outTime / 60 / 60);
        var qm = Math.floor(outTime / 60) - qh * 60;
        var qs = Math.floor(outTime) - qh * 60 * 60 - qm * 60;
        qs = checkTime(qs);
        qm = checkTime(qm);
        qh = checkTime(qh);

        if (status == 0) {
            document.getElementById('time' + id).innerHTML = "bo'sh";
            document.getElementById('outTime' + id).innerHTML = "bo'sh";
            document.getElementById('narx' + id).innerHTML = "bo'sh";
        } else if (status == 1) {
            document.getElementById('outTime' + id).innerHTML =
                qh + ":" + qm + ":" + qs;
            document.getElementById('time' + id).innerHTML =
                h + ":" + m + ":" + s;
            document.getElementById('narx' + id).innerHTML = Math.floor(time / 3.6 * 2);

        } else if (status == 2) {
            document.getElementById('time' + id).innerHTML =
                h + ":" + m + ":" + s;
            document.getElementById('outTime' + id).innerHTML = 'vip';
            document.getElementById('narx' + id).innerHTML = Math.floor(time / 3.6 * 2);
        }
    }
</script>


<table class="table table-striped table-dark table-hover">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Description</th>
        <th scope="col">№</th>
        <th scope="col">Status</th>
        <th scope="col">O'tgan vaqt</th>
        <th scope="col">Qolgan vaqt</th>
        <th scope="col">Narxi</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    include_once 'db-connect.php';
    $sql = "SELECT * FROM `Computers`";
    $result = $conn->query($sql);

    //set free vip or time statuses;
    if ($vip = $_POST['vip_id']) {
        $time = date('Y-m-d H:i:s');
        $sql = "UPDATE `Computers` SET `status` = '2', `time` = '{$time}' WHERE `Computers`.`id` = {$_POST['vip_id']}";
        $conn->query($sql);
        unset($time);
    }
    if ($time = $_POST['time_id']) {
        $sql = "UPDATE `Computers` SET `status` = '1' WHERE `Computers`.`id` = $time";
        $conn->query($sql);
    }
    if ($free = $_POST['free_id']) {
        $sql = "UPDATE `Computers` SET `status` = '0' WHERE `Computers`.`id` = $free";
        $conn->query($sql);
    }
    $conn->query($sql);

    //select row to display
    $sql = "SELECT * FROM `Computers`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
//                0 -> bo'sh        1 -> band       2 -> vip
            $status = $row['status'];
            $i++;
            ?>
            <?php
            switch ($row['status']) {
                case 0:
                    $status = "bo'sh";
                    break;
                case 1:
                    $status = 'band';
                    break;
                case 2:
                    $status = 'vip';
                    break;
                default:
                    $status = 'xatolik';
                    break;
            }
            $time = strtotime($row['time']);
            $time = date('H:m:s', $time);
            $id = $row['id'];
            echo "<tr>
        <th class='td-id' scope='row'>$i</th>
        <td class='td-description'>{$row['description']}</td>
        <td class='td-numofcomp'>{$row['comp-number']}</td>
        <td class='td-status'>$status</td> 
        <td class='td-spendTime'><div class='time' id='time$id'></div></td> 
        <td class='td-outSpendingTime'><div class='outTime' id='outTime$id'></div></td>
        <td class='td-price'><div class='narx' id='narx$id'></div></td>";
            ?>
            <td class='td-action'>
                <form action="" method="post">
                    <input type="hidden" name="vip_id" value="<?= $row['id'] ?>">
                    <input class="btn btn-success" type="submit" name="vip" value="Vip">
                </form>
                <div class="modal-link">
                    <!-- Button trigger modal -->
                    <form action="" method="post">
                        <button type="button" name="time-btn" id="time-btn" class="btn btn-primary m-1"
                                data-toggle="modal" data-target="#exampleModalCenter<?= $row['id'] ?>">
                            Vaqt
                        </button>
                    </form>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModalCenter<?= $row['id'] ?>" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLongTitle<?= $row['id'] ?>">Necha
                                        soatga yoqish
                                        kerak?</h5>
                                    <button type="button" class="close btn btn-danger" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-dark">
                                    <b class="text-center"><?php print_r($row); ?></b><br>
                                    <form action="" method="post">
                                        <label class="m-1" for="hours">hours ( 0-12) && minutes (1-59):</label>
                                        <input class="m-1" type="number" id="hours" name="hours" value="1"
                                               min="0" max="12">
                                        <label class="m-1" for="minutes">:</label>
                                        <input class="m-1" type="number" id="minutes" name="minutes" value="30"
                                               min="0" max="59">
                                        <div class="modal-footer">
                                            <input type="hidden" name="time_id" value="<?= $row['id']; ?>">
                                            <input type="submit" name="save" value="Save" class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <form action="" method="post">
                    <input type="hidden" name="free_id" value="<?= $row['id'] ?>">
                    <input class="btn btn-danger" type="submit" name="free" value="Bo'shatish">
                </form>
            </td>
            </tr>
            <?php
            $status = $row['status'];
            $time = strtotime($row['time']);
            $stopTime = strtotime($row['stopTime']);
            $id = $row['id'];
            echo "<script>setTimeout(checkStartTime, 500, $id, $status, $time, $stopTime)</script>";
        }
    } else {
        echo "0 results";
    }
    ?>
    </tbody>
</table>

<footer class="d-flex justify-content-end container">
    <div class="footer">
        <a href="index.php" class="btn btn-primary">Refresh</a>
        <div class="modal-link">
            <!-- Button trigger modal -->
            <form action="" method="post">
                <button type="button" name="time-btn" id="insert" class="btn btn-success m-1"
                        data-toggle="modal" data-target="#exampleModalInsert">
                    insert
                </button>
            </form>
            <!-- Modal -->
            <div class="modal fade" id="exampleModalInsert" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="exampleModalLongTitle">Kompyuter qo'shish</h5>
                            <button type="button" class="close btn btn-danger" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-dark">
                            <b class="text-center"></b><br>
                            <form action="" method="post">
                                <label class="m-1" for="number">Computer №:</label>
                                <input class="m-1" type="number" id="number" name="number" value="1"
                                       min="1" max="12"><br>
                                <label class="m-1" for="description">Description : </label>
                                <input class="m-1" type="text" id="description" name="description" value="About Comp"
                                       min="1" max="59">
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="insert" value="INSERT" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<br><br><br><br><br>
<form class="d-flex justify-content-end container" action="" method="post">
    <label for="color"></label>
<div class="d-flex w-25">
    <select class="form-select" name="compN" id="compN">
        <option value="" selected>Choose a computer for delete</option>
        <?php
        $sql = "SELECT * FROM `Computers`";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $compN = $row['comp-number'];
                $id = $row['id'];
                echo "<option value='$id'>$compN</option>";
            }
        } else{
            echo '0 results';
        }
        ?>
    </select>
    <button class="btn btn-danger" name="delete" value="delete" type="submit">Delete</button>
</div>
</form>

<?php
if (isset($_POST['insert'])) {
    print_r($_POST);
    $description = $_POST['description'];
    $number = $_POST['number'];
    $sql = "INSERT INTO `Computers` (`id`, `description`, `comp-number`, `status`, `time`, `stopTime`) VALUES (NULL, '$description', $number, '0', NULL, NULL)";
    $conn->query($sql);
    echo '<script type="text/javascript">';
    echo "window.location.href = 'index.php';";
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=\'index.php\'" />';
    echo '</noscript>';
} ?>


<?php
if (isset($_POST['save'])) {
    $time = date('Y-m-d H:i:s');
    $val = time() + $_POST['hours'] * 60 * 60 + $_POST['minutes'] * 60;
    $stopTime = date('Y-m-d H:i:s', $val);
    $id = $_POST['time_id'];
    $sql = "UPDATE `Computers` SET `stopTime` = '{$stopTime}', `status` = '1', `time` = '{$time}' WHERE `Computers`.`id` = '{$id}'";
    $conn->query($sql);
    echo '<script type="text/javascript">';
    echo "window.location.href = 'index.php';";
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=\'index.php\'" />';
    echo '</noscript>';
}
?>


<?php
if (isset($_POST['delete'])) {
    print_r($_POST);
    $id = $_POST['compN'];
    $sql = "DELETE FROM `Computers` WHERE `Computers`.`id` = $id";
    $conn->query($sql);
    $conn->close();
    echo '<script type="text/javascript">';
    echo "window.location.href = 'index.php';";
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=\'index.php\'" />';
    echo '</noscript>';
}
?>

<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
        crossorigin="anonymous"></script>
</body>
</html>