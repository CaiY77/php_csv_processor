<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="main.css">
        <title></title>
    </head>
    <body>
        <table>
            <?php

                $file = fopen('test.csv',"r");
                $line = fgetcsv($file);

                echo "<tr>";
                for($i = 0; $i < count($line) ; $i++ ){
                    echo "<th>$line[$i]</th>";
                }

                echo "</tr>";
                while (($line = fgetcsv($file)) !== FALSE){
                    echo "<tr>";
                    for($i = 0; $i < count($line) ; $i++ ){
                        echo "<td>$line[$i]</td>";
                    }
                    echo "</tr>";
                }

            ?>
        </table>
    </body>
</html>