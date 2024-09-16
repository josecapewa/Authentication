<?php
    $page_title = 'Admin Home Page';
    require_once('load.php');
    page_require_level(1);

    function count_user_by_id($level = null){
        global $db;
        if(isset($level)){
            $sql = "SELECT COUNT(id) AS total from user Where user_level = $level";
            $result = $db->query($sql);
            return $db->fetch_assoc($result);
        } else{
            $sql = "SELECT COUNT(id) AS total from user";
            $result = $db->query($sql);
            return $db->fetch_assoc($result);
        }
        
    }

    $c_user = count_user_by_id();
    $admin = count_user_by_id(1);
    $level2 = count_user_by_id(2);
    $level3 = count_user_by_id(3);
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <?php echo display_msg($msg); ?>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-green">
                <i class="glyphicon glyphicon-user"></i>
            </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2>
                <p class="text-muted">Usuários</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-red">
                <i class="glyphicon glyphicon-user"></i>
            </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top"> <?php  echo $admin['total']; ?> </h2>
                <p class="text-muted"> Administradores </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-blue">
                <i class="glyphicon glyphicon-user"></i>
            </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top"> <?php  echo $level2['total']; ?> </h2>
                <p class="text-muted"> Nivel 1 </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-yellow">
                <i class="glyphicon glyphicon-user"></i>
            </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top"> <?php  echo $level3['total']; ?> </h2>
                <p class="text-muted"> Nivel 2 </p>
            </div>
        </div>
    </div>
</div>
    
    




<?php include_once('layouts/footer.php'); ?>
