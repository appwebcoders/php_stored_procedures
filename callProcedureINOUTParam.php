<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['emailID'])) {
        try {
            $email_ID = $_GET['emailID'];
            // Calling a procedure to select the data 
            include 'DBConnect.php';
            $CALL_PROCEDURE_QUERY_ONE  =  "CALL `get_all_user_having_same_email`(:email_ID, @userCount); ";
            $select__record__statement = $con->prepare($CALL_PROCEDURE_QUERY_ONE);
            $select__record__statement->bindParam(':email_ID',$email_ID,PDO::PARAM_STR);
            $select__record__statement->execute();

            $SELECT_DATA  =  "SELECT @userCount AS `totalUsers`;";
            $Select__data__record__statement = $con->prepare($SELECT_DATA);
            $Select__data__record__statement->execute();
            $user__data = $Select__data__record__statement->fetchAll(PDO::FETCH_ASSOC);
            if ($user__data) {
                http_response_code(200);
                $server__response__success = array(
                    "code" => http_response_code(),
                    "status" => true,
                    "message" => sizeof($user__data) . " Records Found",
                    "data" => $user__data
                );
                echo json_encode($server__response__success);
            } else {
                http_response_code(404);
                $server__response__error = array(
                    "code" => http_response_code(),
                    "status" => false,
                    "message" => "No Records Found"
                );
                echo json_encode($server__response__error);
            }
            $con = null; // close the database connection
        } catch (Exception $ex) {
            http_response_code(404);
            $server__response__error = array(
                "code" => http_response_code(),
                "status" => false,
                "message" => "Opps!!! Something went wrong " . $ex->getMessage()
            );
            echo json_encode($server__response__error);
        }
    } else {
        http_response_code(404);
        $server__response__error = array(
            "code" => http_response_code(),
            "status" => false,
            "message" => "Invalid Parameter"
        );
        echo json_encode($server__response__error);
    }
} else {
    http_response_code(404);
    $server__response__error = array(
        "code" => http_response_code(),
        "status" => false,
        "message" => "Invalid Request"
    );
    echo json_encode($server__response__error);
}
