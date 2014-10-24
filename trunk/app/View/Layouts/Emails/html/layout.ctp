<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset();
    ?>
</head>
<body>
<div id="container">
    <div id="content">
        <div class="container theme-showcase" role="main" style="margin-top: 60px">
            <div id="unprintable_div">

                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer">
            </div>
        </div>
    </div>
</body>
</html>
