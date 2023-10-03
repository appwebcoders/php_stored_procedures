<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Calling a procedure to select the data 
        include 'DBConnect.php';
        $CALL_PROCEDURE_WITH_OUT_PARAM  =  "CALL `get_users_count`(@userCount);";
        $select__record__statement = $con->prepare($CALL_PROCEDURE_WITH_OUT_PARAM);
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
                "No_of_users" => $user__data
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
        "message" => "Invalid Request"
    );
    echo json_encode($server__response__error);
}
