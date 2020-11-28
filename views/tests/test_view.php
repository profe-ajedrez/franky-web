<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <p>vista test</p>
   <p>Motor</p>
   <p><?php echo $franky->callBehavior('langEngine', ['language']) ?></p><p><?php echo $franky->callBehavior('langEngine', ['description']) ?></p>
   <p><?php echo $franky->callBehavior('langEngine', ['description']) ?></p>
   <p><?php echo $franky->callBehavior('langEngine', ['test']) ?></p>
</body>
</html>