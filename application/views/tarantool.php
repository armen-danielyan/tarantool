<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" />
</head>

<body>

<header>
    <div class="container-fluid">

    </div>
</header>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php echo base_url(); ?>tarantool">Home <span class="sr-only">(current)</span></a></li>
            </ul>
            <div class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" class="form-control" value="user1 (id:100)" disabled>
                </div>
            </div>
        </div>
    </div>
</nav>

<main>
    <div class="container">

        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="upload_loadsheet">Import LoadSheet</label>
                            <input type="file" id="upload_loadsheet" name="upload_loadsheet">
                            <p class="help-block">Select LoadSheet CSV File.</p>
                            <button id="fileupload" class="btn btn-success">Upload</button>
                        </div>
                    </div>

                    <div class="col-md-4">

                    </div>

                    <div class="col-md-4">

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="msg"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" id="uploaded_loadsheet">

                    </div>
                </div>

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