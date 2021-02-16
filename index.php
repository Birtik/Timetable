<?php

$connection = require('conf.php');

spl_autoload_register(function ($class){
    require "Class/{$class}.php";
});

if(isset($_GET['id'])){

    $sql = '';

    if(isset($_GET['dev'])){
        $sql = "UPDATE `trainings` SET `dev` = 1 WHERE id = :id";
    }
    else if(isset($_GET['train'])){
        $sql = "UPDATE `trainings` SET `train` = 1 WHERE id = :id";
    }
    else if(isset($_GET['add'])){
        $sql = "UPDATE `trainings` SET `add` = 0 WHERE id = :id";
    }

    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":id",$_GET['id'],PDO::PARAM_INT);
    $stmt->execute();
    
    header('Location: /');
}

Generate::generateDays($connection);
$days = Generate::getDays($connection);

?>

<html>
<head>
</head>
<body>
<center>
<h1><?php echo getdate()['month']?></h1>

<table border="1px">
<tr>
    <th>Data</th>
    <th>Attribute1</th>
    <th>Attribute2</th>
    <th>Attribute3</th>
</tr>

<?php foreach($days as $day) : ?>
<tr>
    <td><?php echo $day['date'] ?></td>
    <td>
        <?php if($day['dev']==0) : ?> <a href="?id=<?php echo $day['id'] ?>&dev=1" style="color:red"> <?php endif;?>
        <?php echo $day['dev'] ? "Tak" : "Nie" ?>
        </a>
    </td>
    <td>
        <?php if($day['train']==0) : ?> <a href="?id=<?php echo $day['id'] ?>&train=1" style="color:red"> <?php endif;?>
        <?php echo $day['train'] ? "Tak" : "Nie" ?>
        </a>
    </td>
    <td>
        <?php if($day['add']==1) : ?> <a href="?id=<?php echo $day['id'] ?>&add=1" style="color:red"> <?php endif;?>
        <?php echo $day['add'] ? "Tak" : "Nie" ?>
        </a>
    </td>
</tr>
<?php endforeach; ?>
<tr>
    <td>Ilość</td>
    <td><?php echo Generate::getCountOfDoneDays($connection,1)."/".Generate::checkMonth(Generate::getMonth());?></td>
    <td><?php echo Generate::getCountOfDoneDays($connection,2)."/".Generate::checkMonth(Generate::getMonth());?></td>
    <td><?php echo Generate::getCountOfDoneDays($connection,3)."/".Generate::checkMonth(Generate::getMonth());?></td>
</tr>
<tr>
    <td>Wykonanie</td>
    <td><?php echo Generate::getProgress($connection,1);?>%</td>
    <td><?php echo Generate::getProgress($connection,2);?>%</td>
    <td><?php echo Generate::getProgress($connection,3);?>%</td>
</tr>
</table>

<ol>
    

</ol>

</center>
</body>
</html>
