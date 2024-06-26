<?php


namespace Dao\Admin;

use Dao\Table;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use DateTime;

class Ventas extends Table
{
    public static function getAll() {
        $sqlstr= "SELECT s.sale_id, s.sale_date , concat(c.customer_name, ' ', c.customer_lastname) 'customer',
        s.sale_isv, s.sale_subtotal, round(sum((sd.sale_quantity * sd.sale_price) + (sale_isv * (sd.sale_quantity * sd.sale_price))),2) 'sale_total' , s.sale_status
        FROM sales_details sd inner join sales s on sd.sale_id= s.sale_id
        inner join customers c on c.customer_id= s.customer_id
         group by s.sale_id;
        ";
        return self::obtenerRegistros($sqlstr, array());
    }

    public static function insertVenta($customer_id, $sale_isv, $sale_subtotal, $sale_status, $sale_order_id) {
        $sqlstr= "INSERT INTO sales
        (
        `sale_date`,
        `customer_id`,
        `sale_isv`,
        `sale_subtotal`,
        `sale_status`,
        `sale_order_id`)
        VALUES
        (
         now(),
        :customer_id,
        :sale_isv,
        :sale_subtotal,
        :sale_status,
        :sale_order_id
        )";


        $sqlParams= [
            "customer_id" => $customer_id, 
            "sale_isv" => $sale_isv, 
            "sale_subtotal" => $sale_subtotal,
            "sale_status" => $sale_status,
            "sale_order_id" => $sale_order_id
        ]; 

        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function insertarDetalle($sale_id, $product_id, $sale_quantity, $sale_price) {
        $sqlstr= "INSERT INTO `sales_details`
        (`sale_id`,
        `product_id`,
        `sale_quantity`,
        `sale_price`)
        VALUES
        (:sale_id,
        :product_id,
        :sale_quantity,
        :sale_price);
        ";

        $sqlParams= [
            "sale_id" => $sale_id, 
            "product_id" => $product_id,
            "sale_quantity" => $sale_quantity,
            "sale_price" => $sale_price
        ];

        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function getLastSaleId () {
        $sqlstr= "select MAX(sale_id) sale_id from sales;";

        return self::obtenerUnRegistro($sqlstr, array());
    }

    public static function getClientesCombo() {
        $sqlstr= "SELECT customer_id,CONCAT(customer_name, ' ', customer_lastname) as 'Nombre', s.useremail from customers c 
        inner join usuario s on c.usercod= s.usercod;";

        return self::obtenerRegistros($sqlstr, array());
    }

    public static function getAllProductsI() {
        $sqlstr= "SELECT i.product_id, p.product_name, p.product_price, i.inventory_size, i.inventory_gender 
        FROM inventory i inner join products p on i.product_id=p.product_id;";

        return self::obtenerRegistros($sqlstr, array());
    }

    public static function lessInventory($quantity, $id, $gender, $size) {
        $sqlstr= "SELECT lessInventory(:id, :quantity, :gender, :size);";

        $sqlParams= [
            "id" => $id, 
            "quantity" => $quantity, 
            "gender" => $gender, 
            "size" => $size
        ];

        return self::obtenerUnRegistro($sqlstr, $sqlParams);

    }

    public static function plusInventory($quantity, $id, $gender, $size) {
        $sqlstr= "SELECT plusInventory(:id, :quantity, :gender, :size);";

        $sqlParams= [
            "id" => $id, 
            "quantity" => $quantity, 
            "gender" => $gender, 
            "size" => $size
        ];

        return self::obtenerUnRegistro($sqlstr, $sqlParams);

    }

    public static function getDetalleVentaBySale($sale_id) {
        $sqlstr= "SELECT P.product_id, P.product_name, SD.sale_price, SD.sale_quantity FROM sales_details SD inner join products P on SD.product_id= P.product_id
        WHERE SD.sale_id= :sale_id;";

        $sqlParams= [
            "sale_id" => $sale_id
        ];

        return self::obtenerRegistros($sqlstr, $sqlParams);
    }

    public static function getVentaById($sale_id) {
        $sqlstr= "SELECT sale_id,  customer_id as cus_id, sale_status FROM sales WHERE sale_id = :sale_id;";
        $sqlParams= [
            "sale_id" => $sale_id
        ];

        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }

    public static function updateStatusVenta($sale_id, $sale_status) {
        $sqlstr= "UPDATE sales SET sale_status= :sale_status where sale_id= :sale_id; ";
        $sqlParams= [
            "sale_status" => $sale_status,
            "sale_id" => $sale_id
        ];

        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function getCustomerId($userid) {
        $sqlstr= "SELECT customer_id, CONCAT(customer_name, ' ', customer_lastname) as customer_name, useremail FROM customers c
        inner join usuario us on c.usercod= us.usercod
         where c.usercod= :userid ;";
        $sqlParams= [
            "userid"=> $userid
        ];

        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }

    public static function getInfoCustomer($userid) {
        $sqlstr= "SELECT customer_id, customer_name, customer_lastname,customer_address,customer_postal_code,customer_country, 
        customer_city,customer_phone_number,us.useremail FROM customers c
                inner join usuario us on c.usercod= us.usercod
                 where c.usercod= :userid ;";
        $sqlParams= [
          "userid"=> $userid
      ];

      return self::obtenerUnRegistro($sqlstr, $sqlParams);

    }

    public static function sendEmail($email, $arregloVenta, $orderid, $customer_name ) {
        $aquidata= "";
        $fecha= (new DateTime())->format('Y-m-d H:i');
        $total= 0;
        foreach($arregloVenta as $key => $value) {
                $product_name= $value["product_name"];
                $quantity= $value["quantity"];
                $price= $value["product_price"];
                $total_price= $quantity * $price;
                $total+= $total_price;
                $aquidata.="       
                <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
                <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
                  <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
                    <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
                    
              <!--[if (mso)|(IE)]><td align=\"center\" width=\"199\" style=\"width: 199px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #dfdfdf;border-bottom: 1px solid #dfdfdf;\" valign=\"top\"><![endif]-->
              <div class=\"u-col u-col-33p33\" style=\"max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;\">
                <div style=\"height: 100%;width: 100% !important;\">
                <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #dfdfdf;border-bottom: 1px solid #dfdfdf;\"><!--<![endif]-->
                
              <table id=\"u_content_text_17\" style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
                <tbody>
                  <tr>
                    <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                      
                <div class=\"v-text-align\" style=\"color: #b3b3b3; line-height: 140%; text-align: center; word-wrap: break-word;\">
                  <p style=\"font-size: 14px; line-height: 140%;\">$product_name</p>
                </div>
              
                    </td>
                  </tr>
                </tbody>
              </table>
              
                <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
                </div>
              </div>
              <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]><td align=\"center\" width=\"199\" style=\"width: 199px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #dfdfdf;border-bottom: 1px solid #dfdfdf;\" valign=\"top\"><![endif]-->
              <div class=\"u-col u-col-33p33\" style=\"max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;\">
                <div style=\"height: 100%;width: 100% !important;\">
                <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #dfdfdf;border-bottom: 1px solid #dfdfdf;\"><!--<![endif]-->
                
              <table id=\"u_content_text_18\" style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
                <tbody>
                  <tr>
                    <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:18px 10px 15px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                      
                <div class=\"v-text-align\" style=\"color: #313131; line-height: 140%; text-align: center; word-wrap: break-word;\">
                  <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 18px; line-height: 25.2px;\"><span style=\"line-height: 25.2px; font-size: 18px;\">$quantity</span></span></p>
                </div>
              
                    </td>
                  </tr>
                </tbody>
              </table>
              
                <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
                </div>
              </div>
              <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]><td align=\"center\" width=\"200\" style=\"width: 200px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #dfdfdf;\" valign=\"top\"><![endif]-->
              <div class=\"u-col u-col-33p33\" style=\"max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;\">
                <div style=\"height: 100%;width: 100% !important;\">
                <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #dfdfdf;\"><!--<![endif]-->
                
              <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
                <tbody>
                  <tr>
                    <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:17px 10px 16px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                      
                <div class=\"v-text-align\" style=\"color: #4b4a4a; line-height: 140%; text-align: center; word-wrap: break-word;\">
                  <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 18px; line-height: 25.2px;\"><span style=\"line-height: 25.2px; font-size: 18px;\">$ $total_price</span></span></p>
                </div>
              
                    </td>
                  </tr>
                </tbody>
              </table>
              
                <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
                </div>
              </div>
              <!--[if (mso)|(IE)]></td><![endif]-->
                    <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                  </div>
                </div>
              </div>
              
              ";
        }
       
        $mail = new PHPMailer();
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // or 587
        $mail->Username = "ashionshopcommerce@gmail.com";
        $mail->Password = "bvyplfbfkgtwaguo";
        $mail->SetFrom($email);
        $mail->Subject = "Order Confirmation: ".$orderid;
        $mail->Body=  "
        <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">
        <head>
        <!--[if gte mso 9]>
        <xml>
          <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
        </xml>
        <![endif]--> 
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
          <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
          <meta name=\"x-apple-disable-message-reformatting\">
          <!--[if !mso]><!--><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><!--<![endif]-->
          <title></title>
          
            <style type=\"text/css\">
              @media only screen and (min-width: 620px) {
          .u-row {
            width: 600px !important;
          }
          .u-row .u-col {
            vertical-align: top;
          }
        
          .u-row .u-col-31 {
            width: 186px !important;
          }
        
          .u-row .u-col-31p5 {
            width: 189px !important;
          }
        
          .u-row .u-col-33p33 {
            width: 199.98px !important;
          }
        
          .u-row .u-col-35p67 {
            width: 214.02px !important;
          }
        
          .u-row .u-col-68p5 {
            width: 411px !important;
          }
        
          .u-row .u-col-100 {
            width: 600px !important;
          }
        
        }
        
        @media (max-width: 620px) {
          .u-row-container {
            max-width: 100% !important;
            padding-left: 0px !important;
            padding-right: 0px !important;
          }
          .u-row .u-col {
            min-width: 320px !important;
            max-width: 100% !important;
            display: block !important;
          }
          .u-row {
            width: calc(100% - 40px) !important;
          }
          .u-col {
            width: 100% !important;
          }
          .u-col > div {
            margin: 0 auto;
          }
        }
        body {
          margin: 0;
          padding: 0;
        }
        
        table,
        tr,
        td {
          vertical-align: top;
          border-collapse: collapse;
        }
        
        p {
          margin: 0;
        }
        
        .ie-container table,
        .mso-container table {
          table-layout: fixed;
        }
        
        * {
          line-height: inherit;
        }
        
        a[x-apple-data-detectors='true'] {
          color: inherit !important;
          text-decoration: none !important;
        }
        
        table, td { color: #000000; } a { color: #0000ee; text-decoration: underline; } @media (max-width: 480px) { #u_content_text_14 .v-container-padding-padding { padding: 15px 10px !important; } #u_content_text_15 .v-container-padding-padding { padding: 10px !important; } #u_content_text_17 .v-container-padding-padding { padding: 15px 10px !important; } #u_content_text_18 .v-container-padding-padding { padding: 10px !important; } #u_content_text_20 .v-container-padding-padding { padding: 15px 10px !important; } #u_content_text_21 .v-container-padding-padding { padding: 10px !important; } #u_content_text_23 .v-container-padding-padding { padding: 15px 10px !important; } #u_content_text_24 .v-container-padding-padding { padding: 10px !important; } #u_content_text_26 .v-text-align { text-align: center !important; } #u_content_text_27 .v-text-align { text-align: center !important; } #u_content_text_28 .v-container-padding-padding { padding: 25px 10px 0px !important; } #u_content_divider_6 .v-container-padding-padding { padding: 10px !important; } }
            </style>
          
          
        
        <!--[if !mso]><!--><link href=\"https://fonts.googleapis.com/css?family=Cabin:400,700&display=swap\" rel=\"stylesheet\" type=\"text/css\"><link href=\"https://fonts.googleapis.com/css?family=Lobster+Two:400,700&display=swap\" rel=\"stylesheet\" type=\"text/css\"><!--<![endif]-->
        
        </head>
        
        <body class=\"clean-body u_body\" style=\"margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #dfdfdf;color: #000000\">
          <!--[if IE]><div class=\"ie-container\"><![endif]-->
          <!--[if mso]><div class=\"mso-container\"><![endif]-->
          <table style=\"border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #dfdfdf;width:100%\" cellpadding=\"0\" cellspacing=\"0\">
          <tbody>
          <tr style=\"vertical-align: top\">
            <td style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top\">
            <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td align=\"center\" style=\"background-color: #dfdfdf;\"><![endif]-->
            
       
        <div class=\"u-row-container\" style=\"padding: 0px;background-image: url('https://assets.unlayer.com/projects/0/1659426311133-image-8.jpeg');background-repeat: no-repeat;background-position: center top;background-color: #dfdfdf\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-image: url('https://assets.unlayer.com/projects/0/1659426311133-image-8.jpeg'');background-repeat: no-repeat;background-position: center top;background-color: #dfdfdf;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: transparent;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:65px 10px 10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #ffffff; line-height: 150%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 150%; text-align: center;\"><span style=\"font-size: 36px; line-height: 54px;\">Dear, <span style=\"font-family: Cabin, sans-serif; line-height: 54px; color: #24771f; background-color: #ffffff; font-size: 36px;\">$customer_name</span></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:0px 10px 40px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #ffffff; line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 24px; line-height: 33.6px;\">THANKS FOR YOUR ORDER!</span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: transparent\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ebebeb;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: transparent;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ebebeb;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <table height=\"0px\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 0px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
            <tbody>
              <tr style=\"vertical-align: top\">
                <td style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
                  <span>&#160;</span>
                </td>
              </tr>
            </tbody>
          </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: transparent\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ebebeb;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: transparent;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ebebeb;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"199\" style=\"width: 199px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #e1e1e1;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-33p33\" style=\"max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #e1e1e1;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:20px 10px 0px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
          <tr>
            <td class=\"v-text-align\" style=\"padding-right: 0px;padding-left: 0px;\" align=\"center\">
              
              <img align=\"center\" border=\"0\" src=\"https://cdn.templates.unlayer.com/assets/1619163636909-icon1.jpg\" alt=\"Mark\" title=\"Mark\" style=\"outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 34px;\" width=\"34\"/>
              
            </td>
          </tr>
        </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:6px 10px 20px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 16px; line-height: 22.4px;\"><span style=\"color: #969696; line-height: 22.4px; font-size: 16px;\">Order No:</span></span></p>
        <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 16px; line-height: 22.4px;\"><strong><span style=\"color: #595959; line-height: 22.4px; font-size: 16px;\">$orderid</span></strong></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"213\" style=\"width: 213px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #e1e1e1;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-35p67\" style=\"max-width: 320px;min-width: 214px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #e1e1e1;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:20px 10px 0px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
          <tr>
            <td class=\"v-text-align\" style=\"padding-right: 0px;padding-left: 0px;\" align=\"center\">
              
              <img align=\"center\" border=\"0\" src=\"https://cdn.templates.unlayer.com/assets/1619163644933-icon2.jpg\" alt=\"Calendar\" title=\"Calendar\" style=\"outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 34px;\" width=\"34\"/>
              
            </td>
          </tr>
        </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:6px 10px 20px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #565656; line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 16px; line-height: 22.4px;\"><span style=\"color: #969696; line-height: 22.4px; font-size: 16px;\">Order Date:</span></span></p>
        <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 16px; line-height: 22.4px;\"><strong>$fecha</strong></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"186\" style=\"width: 186px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-31\" style=\"max-width: 320px;min-width: 186px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:20px 10px 0px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
          <tr>
            <td class=\"v-text-align\" style=\"padding-right: 0px;padding-left: 0px;\" align=\"center\">
              
              <img align=\"center\" border=\"0\" src=\"https://cdn.templates.unlayer.com/assets/1619163651651-icon3.jpg\" alt=\"Dollar\" title=\"Dollar\" style=\"outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 23px;\" width=\"23\"/>
              
            </td>
          </tr>
        </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:6px 10px 20px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 16px; line-height: 22.4px;\"><span style=\"color: #969696; line-height: 22.4px; font-size: 16px;\">Total:</span></span></p>
        <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 16px; line-height: 22.4px;\">$<span style=\"color: #595959; font-size: 16px; line-height: 22.4px;\"><strong> $total</strong></span></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <h2 class=\"v-text-align\" style=\"margin: 0px; color: #56b501; line-height: 140%; text-align: center; word-wrap: break-word; font-weight: normal; font-family: arial,helvetica,sans-serif; font-size: 29px;\">
            <strong>Order Detail</strong>
          </h2>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #56b501;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #56b501;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"199\" style=\"width: 199px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #48a600;border-bottom: 1px solid #48a600;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-33p33\" style=\"max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #48a600;border-bottom: 1px solid #48a600;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #ffffff; line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-family: georgia, palatino; font-size: 18px; line-height: 25.2px;\"><span style=\"line-height: 25.2px; font-size: 18px;\">Item</span></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"199\" style=\"width: 199px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #48a600;border-bottom: 1px solid #48a600;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-33p33\" style=\"max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 1px solid #48a600;border-bottom: 1px solid #48a600;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #ffffff; line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-family: georgia, palatino; font-size: 18px; line-height: 25.2px;\"><span style=\"line-height: 25.2px; font-size: 18px;\">Quantity</span></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"200\" style=\"width: 200px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #48a600;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-33p33\" style=\"max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #48a600;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #ffffff; line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-family: georgia, palatino; font-size: 18px; line-height: 25.2px;\"><span style=\"line-height: 25.2px; font-size: 18px;\">Total</span></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
    
        
       $aquidata
        
        
        
    
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"411\" style=\"width: 411px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #dfdfdf;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-68p5\" style=\"max-width: 320px;min-width: 411px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #dfdfdf;\"><!--<![endif]-->
          
        <table id=\"u_content_text_26\" style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px 10px 12px 15px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #408b06; line-height: 140%; text-align: left; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 16px; line-height: 22.4px;\">Payment method:</span></p>
        <p style=\"font-size: 14px; line-height: 140%;\"><strong><span style=\"font-size: 16px; line-height: 22.4px;\">PAYPAL</span></strong></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"189\" style=\"width: 189px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #dfdfdf;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-31p5\" style=\"max-width: 320px;min-width: 189px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 1px solid #dfdfdf;\"><!--<![endif]-->
          
        <table id=\"u_content_text_27\" style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:18px 10px 23px 9px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #48a600; line-height: 140%; text-align: left; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 18px; line-height: 25.2px;\"><strong>Total: $ $total</strong></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table id=\"u_content_text_28\" style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:40px 10px 0px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #48a600; line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\"><span style=\"font-size: 30px; line-height: 42px; font-family: 'Lobster Two', cursive;\"><strong>Order Accepted</strong></span></p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #858585; line-height: 140%; text-align: center; word-wrap: break-word;\">
           
        <p style=\"font-size: 14px; line-height: 140%;\">We are happy to let you know that we have received your order.</p>
        <p style=\"font-size: 14px; line-height: 140%;\">Once your package ships, we will send you an email with a tracking number and link so you can see the movement of your package.</p>
        <p style=\"font-size: 14px; line-height: 140%;\">If you have any questions, contact us here or call us on 001 834 567 6789</p>
        <p style=\"font-size: 14px; line-height: 140%;\">We are here to help!</p>
        
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
        <div align=\"center\">
          <div style=\"display: table; max-width:167px;\">
          <!--[if (mso)|(IE)]><table width=\"167\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"border-collapse:collapse;\" align=\"center\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"border-collapse:collapse; mso-table-lspace: 0pt;mso-table-rspace: 0pt; width:167px;\"><tr><![endif]-->
          
            
            <!--[if (mso)|(IE)]><td width=\"32\" style=\"width:32px; padding-right: 10px;\" valign=\"top\"><![endif]-->
            <table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"32\" height=\"32\" style=\"width: 32px !important;height: 32px !important;display: inline-block;border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 10px\">
              <tbody><tr style=\"vertical-align: top\"><td align=\"left\" valign=\"middle\" style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top\">
                <a href=\"https://facebook.com/\" title=\"Facebook\" target=\"_blank\">
                  <img src=\"https://assets.unlayer.com/projects/0/1659427056060-image-1.png\" alt=\"Facebook\" title=\"Facebook\" width=\"32\" style=\"outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important\">
                </a>
              </td></tr>
            </tbody></table>
            <!--[if (mso)|(IE)]></td><![endif]-->
            
            <!--[if (mso)|(IE)]><td width=\"32\" style=\"width:32px; padding-right: 10px;\" valign=\"top\"><![endif]-->
            <table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"32\" height=\"32\" style=\"width: 32px !important;height: 32px !important;display: inline-block;border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 10px\">
              <tbody><tr style=\"vertical-align: top\"><td align=\"left\" valign=\"middle\" style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top\">
                <a href=\"https://twitter.com/\" title=\"Twitter\" target=\"_blank\">
                  <img src=\"https://assets.unlayer.com/projects/0/1659427107333-image-2.png\" alt=\"Twitter\" title=\"Twitter\" width=\"32\" style=\"outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important\">
                </a>
              </td></tr>
            </tbody></table>
            <!--[if (mso)|(IE)]></td><![endif]-->
            
            <!--[if (mso)|(IE)]><td width=\"32\" style=\"width:32px; padding-right: 10px;\" valign=\"top\"><![endif]-->
            <table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"32\" height=\"32\" style=\"width: 32px !important;height: 32px !important;display: inline-block;border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 10px\">
              <tbody><tr style=\"vertical-align: top\"><td align=\"left\" valign=\"middle\" style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top\">
                <a href=\"https://instagram.com/\" title=\"Instagram\" target=\"_blank\">
                  <img src=\"https://assets.unlayer.com/projects/0/1659427164065-image-4.png\" alt=\"Instagram\" title=\"Instagram\" width=\"32\" style=\"outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important\">
                </a>
              </td></tr>
            </tbody></table>
            <!--[if (mso)|(IE)]></td><![endif]-->
            
            <!--[if (mso)|(IE)]><td width=\"32\" style=\"width:32px; padding-right: 0px;\" valign=\"top\"><![endif]-->
            <table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"32\" height=\"32\" style=\"width: 32px !important;height: 32px !important;display: inline-block;border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 0px\">
              <tbody><tr style=\"vertical-align: top\"><td align=\"left\" valign=\"middle\" style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top\">
                <a href=\"https://linkedin.com/\" title=\"LinkedIn\" target=\"_blank\">
                  <img src=\"https://assets.unlayer.com/projects/0/1659427141338-image-3.png\" alt=\"LinkedIn\" title=\"LinkedIn\" width=\"32\" style=\"outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important\">
                </a>
              </td></tr>
            </tbody></table>
            <!--[if (mso)|(IE)]></td><![endif]-->
            
            
            <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
          </div>
        </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <table height=\"0px\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #e9e9e9;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
            <tbody>
              <tr style=\"vertical-align: top\">
                <td style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
                  <span>&#160;</span>
                </td>
              </tr>
            </tbody>
          </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
        <div class=\"v-text-align\" align=\"center\">
          <!--[if mso]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;font-family:arial,helvetica,sans-serif;\"><tr><td class=\"v-text-align\" style=\"font-family:arial,helvetica,sans-serif;\" align=\"center\"><v:roundrect xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:w=\"urn:schemas-microsoft-com:office:word\" href=\"\" style=\"height:37px; v-text-anchor:middle; width:118px;\" arcsize=\"11%\" stroke=\"f\" fillcolor=\"#3AAEE0\"><w:anchorlock/><center style=\"color:#FFFFFF;font-family:arial,helvetica,sans-serif;\"><![endif]-->
            <a href=\"\" target=\"_blank\" style=\"box-sizing: border-box;display: inline-block;font-family:arial,helvetica,sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #3AAEE0; border-radius: 4px;-webkit-border-radius: 4px; -moz-border-radius: 4px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;\">
              <span style=\"display:block;padding:10px 20px;line-height:120%;\">SHOP NOW</span>
            </a>
          <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
        </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <table height=\"0px\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #e9e9e9;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
            <tbody>
              <tr style=\"vertical-align: top\">
                <td style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
                  <span>&#160;</span>
                </td>
              </tr>
            </tbody>
          </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:15px 10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <div class=\"v-text-align\" style=\"color: #646464; line-height: 140%; text-align: center; word-wrap: break-word;\">
            <p style=\"font-size: 14px; line-height: 140%;\">All rights reserved. Ashion Shop.</p>
          </div>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <table height=\"0px\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #e9e9e9;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
            <tbody>
              <tr style=\"vertical-align: top\">
                <td style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
                  <span>&#160;</span>
                </td>
              </tr>
            </tbody>
          </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #eaeaea\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #eaeaea;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <table height=\"0px\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 0px solid #e9e9e9;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
            <tbody>
              <tr style=\"vertical-align: top\">
                <td style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
                  <span>&#160;</span>
                </td>
              </tr>
            </tbody>
          </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: #dfdfdf\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: #dfdfdf;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: #ffffff;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
        <table id=\"u_content_divider_6\" style=\"font-family:arial,helvetica,sans-serif;\" role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
          <tbody>
            <tr>
              <td class=\"v-container-padding-padding\" style=\"overflow-wrap:break-word;word-break:break-word;padding:15px 10px;font-family:arial,helvetica,sans-serif;\" align=\"left\">
                
          <table height=\"0px\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 0px solid #e9e9e9;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
            <tbody>
              <tr style=\"vertical-align: top\">
                <td style=\"word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%\">
                  <span>&#160;</span>
                </td>
              </tr>
            </tbody>
          </table>
        
              </td>
            </tr>
          </tbody>
        </table>
        
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 25px 0px 20px;background-color: transparent\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 25px 0px 20px;background-color: transparent;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: transparent;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
        
        <div class=\"u-row-container\" style=\"padding: 0px;background-color: transparent\">
          <div class=\"u-row\" style=\"Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;\">
            <div style=\"border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;\">
              <!--[if (mso)|(IE)]><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td style=\"padding: 0px;background-color: transparent;\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:600px;\"><tr style=\"background-color: transparent;\"><![endif]-->
              
        <!--[if (mso)|(IE)]><td align=\"center\" width=\"600\" style=\"width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\" valign=\"top\"><![endif]-->
        <div class=\"u-col u-col-100\" style=\"max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;\">
          <div style=\"height: 100%;width: 100% !important;\">
          <!--[if (!mso)&(!IE)]><!--><div style=\"padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;\"><!--<![endif]-->
          
          <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
              <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        
        
            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
            </td>
          </tr>
          </tbody>
          </table>
          <!--[if mso]></div><![endif]-->
          <!--[if IE]></div><![endif]-->
        </body>
        
        </html>
        ";
       $mail->AltBody= "Hola Prueba";
       $mail->IsHTML(true);
        $mail->AddAddress($email);
        if(!$mail->Send()) {
           return $errorMail= false;
        } else {
          error_log($mail->ErrorInfo) ;
          return $errorMail= true;
        }
    }
    

}
