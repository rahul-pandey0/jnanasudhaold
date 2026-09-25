<!DOCTYPE html>
<html>
<head>
    <title>NEET 2021 Rank List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f4f4f4;
        }

        h2, h3 {
            text-align: center;
            color: #435d7d;
        }

        .form-box {
            background: #fff;
            padding: 15px;
            border: 1px solid #ccc;
            width: 60%;
            margin: 0 auto 20px auto;
            border-radius: 3px;
            box-shadow: 0 1px 10px 4px rgba(181, 180, 180, 0.3);
        }

        select, button {
            padding: 6px 10px;
            font-size: 14px;
            margin-top: 5px;
        }

        button {
            background: #428bca;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background: #3071a9;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 1px 10px 4px rgba(181, 180, 180, 0.3);
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #e9e9e9;
            padding: 8px 10px;
            text-align: center;
            font-size: 14px;
        }

        table th {
            background: #435d7d;
            color: #fff;
        }

        table tr:nth-child(odd) {
            background-color: #fcfcfc;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        table td a {
            color: #566787;
            text-decoration: none;
            font-weight: bold;
        }

        table td a:hover {
            color: #2196F3;
        }

        p {
            text-align: center;
            font-size: 14px;
            color: red;
        }

        @media (max-width: 767px) {
            .form-box {
                width: 90%;
            }

            table {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<h2>NEET 2021 Rank List</h2>

<div class="form-box">
    <form method="post" action="">
        <label><b>Select Quiz</b></label><br>
        <select name="category" required>
            <option value="">-- Select Quiz --</option>
            <?php foreach ($result_sub as $row) { ?>
               <option value="<?php echo $row['id']; ?>"
                <?php if (!empty($_POST['category']) && $_POST['category'] == $row['id']) echo 'selected'; ?>>
                <?php echo $row['subject']; ?>
            </option>
            <?php } ?>
        </select>

        <br><br>

        <label><b>Select Batch</b></label><br>
        <select name="batch">
            <option value="all">All</option>
            <option value="JNANASUDHAIPU" <?php if (!empty($_POST['batch']) && $_POST['batch']=='JNANASUDHAIPU') echo 'selected'; ?>>JNANASUDHA IPU</option>
            <option value="JNANASUDHAIIPU" <?php if (!empty($_POST['batch']) && $_POST['batch']=='JNANASUDHAIIPU') echo 'selected'; ?>>JNANASUDHA IIPU</option>
        </select>

        <br><br>

        <button type="submit">Get Rank</button>
    </form>
</div>

<?php if (!empty($result)) { ?>

   <h3>Rank List : <?php echo $result[0]['subject']; ?></h3>

    <table>
        <tr>
            <th>Rank</th>
            <th>Roll No</th>
            <th>Name</th>
            <th>Batch</th>
            <th>Total Marks</th>
            <th>Accommodation</th>
        </tr>

        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo $row['rank']; ?></td>
                <td><?php echo $row['rollno']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['batch']; ?></td>
                <td><?php echo $row['total']; ?></td>
                <td><?php echo $row['accommodation']; ?></td>
            </tr>
        <?php } ?>
    </table>

<?php } elseif (!empty($_POST['category'])) { ?>

    <p>No records found.</p>

<?php } ?>

</body>
</html>
