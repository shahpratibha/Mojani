<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Design with Input and Labels</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <style>
            body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
    margin: 0;
}

.card {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 400px;
}

.card-body {
    padding: 10px;
}


.form-group label {
    width: 100px;
    font-weight: bold;
    margin-right: 10px;
}

.form-group select,
.form-group input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

        </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="input1" class="col-form-label">District</label>
                    <div>
                        <select class="form-control" name="input1" id="input1"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input2" class="col-form-label">Taluka</label>
                    <div>
                        <select class="form-control" name="input2" id="input2"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input3" class="col-form-label">Village</label>
                    <div>
                        <select class="form-control" name="input3" id="input3"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input4" class="col-form-label">Survey No</label>
                    <div>
                        <input type="text" class="form-control" name="input4" id="input4">
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
