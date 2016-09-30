<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>

<header>
    <div class="container-fluid">

    </div>
</header>

<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php if($upload_data){
                    $keys = array_keys($upload_data[0]); ?>
                    <table class="table table-striped">
                        <tr>
                            <?php foreach ($keys as $key) { ?>
                                <th><?php echo $key; ?></th>
                            <?php } ?>
                        </tr>
                        <?php foreach ($upload_data as $row) { ?>
                            <tr>
                                <?php foreach($keys as $key) { ?>
                                    <td><?php echo $row[$key] ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
                <?php }

                if($error){
                    var_dump($error);
                } ?>

            </div>
        </div>
    </div>
</main>

<footer>
    <div class="container-fluid">

    </div>
</footer>

</body>
</html>