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
                // phpinfo();

                $file = fopen('products.csv',"r");

                // Make API call to get USD to CAD rates and store rate in $rate
                $response = file_get_contents('https://free.currconv.com/api/v7/convert?q=USD_CAD&compact=ultra&apiKey=404b63b19e94bb7c0f12');
                $response = json_decode($response);
                $rate = $response->USD_CAD;

                //first line will always be header also adding additional requested Total Profit USD, Total Profit CAD, Profit margin
                $line = fgetcsv($file);

                // column order isn't garuenteed, we have to note which coloumn represents what
                $qty;
                $cost;
                $price;

                $total_items = 0; // # of items
                $total_qty = 0; // sum of qty
                $price_accum = 0; // used to find average price
                $margin_average = 0; // used to find average margin
                $total_prof_usd = 0; // total USD profit
                $total_prof_cad = 0; // total CAD profit

                echo "<tr>";
                for($i = 0; $i < count($line) ; $i++ ){
                    echo "<th>$line[$i]</th>";

                    switch($line[$i]){
                        case "qty":
                            $qty = $i;
                            break;
                        case "price":
                            $price = $i;
                            break;
                        case "cost":
                            $cost = $i;
                            break;
                    }
                }
                echo "<th>Total Profit (USD)</th>";
                echo "<th>Total Profit (CAD)</th>";
                echo "<th>Profit Margin</th>";


                // adding respective values and making profit calculation and CAD conversion
                echo "</tr>";
                while (($line = fgetcsv($file)) !== FALSE){

                    $total_items++; //counting # of items, used for average

                    echo "<tr>";
                    for($i = 0; $i < count($line) ; $i++ ){
                        echo "<td>$line[$i]</td>";
                    }

                    $total_qty += $line[$qty];
                    $price_accum += $line[$price];

                    //total profit calculation
                    $USD = ($line[$price] - $line[$cost])* $line[$qty];
                    $total_prof_usd += $USD;
                    if ($USD > 0){
                        echo "<td class='profit'>$ $USD</td>";
                    } else {
                        echo "<td class='lost'>$ $USD</td>";
                    }

                    // USD to CAD
                    $CAD = $USD*$rate;
                    $CAD = round($CAD,2,PHP_ROUND_HALF_DOWN);
                    $total_prof_cad += $CAD;

                    if ($CAD > 0){
                        echo "<td class='profit'>$CAD</td>";
                    } else {
                        echo "<td class='lost'>$CAD</td>";
                    }

                    //Profit Margin = ( Revenue - Cost)/Revenue

                    $pm = ($USD-$line[$cost])/$USD;
                    $pm = round($pm,2,PHP_ROUND_HALF_DOWN);
                    $margin_average += $pm;

                    if ($pm > 0){
                        echo "<td class='profit'>$pm</td>";
                    } else {
                        echo "<td class='lost'>$pm</td>";
                    }
                    
                    echo "</tr>";
                }
                $temp = 0;

                if($total_items != 0){
                    $temp = $price_accum/$total_items;
                }
                echo "<tr><td>Average Price</td><td>$temp</td></tr>";
                
                echo "<tr><td>Total QTY</td><td>$total_qty</td></tr>";

                if($total_items != 0){
                    $temp = $margin_average/$total_items;
                } 
                echo "<tr><td>Average Profit Margin</td><td>$temp</td></tr>";

                echo "<tr><td>Total Profit (USD)</td><td>$ $total_prof_usd</td></tr>";

                echo "<tr><td>Total Profit (CAD)</td><td>$total_prof_cad</td></tr>";

            ?>
        </table>
    </body>
</html>