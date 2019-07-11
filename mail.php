<?php

    $prod_01_variant_01_quantity = (int)$_POST["prod_01_variant_01_quantity"];
    $prod_01_variant_02_quantity = (int)$_POST["prod_01_variant_02_quantity"];
    $prod_01_variant_03_quantity = (int)$_POST["prod_01_variant_03_quantity"];
    $prod_02_variant_01_quantity = (int)$_POST["prod_02_variant_01_quantity"];
    $prod_03_variant_01_quantity = (int)$_POST["prod_03_variant_01_quantity"];
    $prod_03_variant_02_quantity = (int)$_POST["prod_03_variant_02_quantity"];

    $amount = 17*$prod_01_variant_01_quantity+31*$prod_01_variant_02_quantity+45*$prod_01_variant_03_quantity+50*$prod_02_variant_01_quantity+16*$prod_03_variant_01_quantity+50*$prod_03_variant_02_quantity;

    if($amount==0){
        echo "<div style='padding: 15px; background: red; color: #fff;'>Oops! You need to add atleast 1 product. <a href='http://laktasekampagne.de'>Click to get back</a></div>";
        die();
    }


    // Unique order id
    function generate_id() {
        $allowedCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $uid = '';
        $length = 2;
        $max = mb_strlen($allowedCharacters, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $uid .= $allowedCharacters[random_int(0, $max)];
        }
        return $uid;
      
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        # FIX: Replace this email with recipient email
        $mail_to = "zihad0292@gmail.com";
        $subject ='New order from laktasekampagne.de';
        
        # Sender Data
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $order_id = uniqid('JSON-ID-').generate_id();

        //$order_date = '2019-06-26T00:00:00';
        $order_date = date("Y-m-d")."T".date("h:i:s");

             
        # Mail Content
        $content = '{
            "order_id": "'.$order_id.'",
            "currency_code": "EUR",
            "payment_method": "'.$_POST["payment_method"].'", 
            "order_date": "'.$order_date.'",
            "email": "'.$email.'", 
            "invoice": {
                "firstname": "'.$_POST["first-name"].'",
                "lastname": "'.$_POST["surname"].'",
                "address_1": "'.$_POST["streetaddress"] ["apt"].'",
                "zip": "'.$_POST["postcode"].'",
                "city": "'.$_POST["town"].'",
                "country": "'.$_POST["country"].'"
            },
            "shipping": {
                "firstname": "'.$_POST["first-name"].'",
                "lastname": "'.$_POST["surname"].'",
                "address_1": "'.$_POST["streetaddress"] ["apt"].'",
                "zip": "'.$_POST["postcode"].'",
                "city": "'.$_POST["town"].'",
                "country": "'.$_POST["country"].'"
            },
            "order_status_id": 2,
            "order_items": [ 
                {
                    "quantity": '.$prod_01_variant_01_quantity.',
                    "totalprice": 17,
                    "name": " Eine Packung mit 100 Millis für 17 Euro ",
                    "taxrate": 7
                },
                {
                    "quantity": '.$prod_01_variant_02_quantity.',
                    "totalprice": 31,
                    "name": " Eine Packung mit 250 Millis für 31 Euro ",
                    "taxrate": 7
                },
                {
                    "quantity": '.$prod_01_variant_03_quantity.',
                    "totalprice": 45,
                    "name": " Eine Packung mit 500 Millis für 45 Euro ",
                    "taxrate": 7
                },
                {
                    "quantity": '.$prod_02_variant_01_quantity.',
                    "totalprice": 50,
                    "name": " Eine Packung mit 50g Laktasepulver für 50 Euro ",
                    "taxrate": 7
                },
                {
                    "quantity": '.$prod_03_variant_01_quantity.',
                    "totalprice": 16,
                    "name": " Eine Packung mit 16 Gramm für 16 Euro ",
                    "taxrate": 7
                },
                {
                    "quantity": '.$prod_03_variant_02_quantity.',
                    "totalprice": 50,
                    "name": " Eine Packung mit 80 Gramm für 50 Euro ",
                    "taxrate": 7
                }
            ]
        }';

        # email headers.
        $headers = "From: <admin@clients.zihadsweb.com>";

        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business='.rawurlencode("info@laktasekampagne.de").'&currency_code=EUR&amount='.$amount.'&return='.rawurlencode("http://laktasekampagne.de/danke-fuer-deine-bestellung").'&cancel_return='.rawurlencode("http://laktasekampagne.de");

        # Send the email.
        $success = mail($mail_to, $subject, $content, $headers);
        if ($success && $_POST["payment_method"]=="Rechnungszahlung") {

            http_response_code(200);
            header("Location: http://laktasekampagne.de/danke-fuer-deine-bestellung/");

        } elseif ($success && $_POST["payment_method"]=="Paypal") {

            http_response_code(200);
            header("Location:".$paypal_url);

        } else {

            echo "<div style='padding: 15px; background: red; color: #fff;'>Oops! Something went wrong, we couldn't send your message. <a href='http://laktasekampagne.de'>Click to get back</a></div>";

        } 

    } else {
        # Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>








