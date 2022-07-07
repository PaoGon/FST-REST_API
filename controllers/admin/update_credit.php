<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: PUT, OPTIONS");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require __DIR__ . '/../../config/Database.php';
    require __DIR__ . '/../../classes/Service.php';
    require __DIR__ . '/../../accounts/CheckAuth.php';
    require __DIR__ . '/../../helpers/msg.php';
    require __DIR__ . '/../../helpers/calc_days.php';

    $database = new Database();
    $conn = $database->getConnection();
    $obj = new Service($conn);

    // DATA FORM REQUEST
    $data = json_decode(file_get_contents("php://input"));
    $returnData = [];

    // For CROSS-ORGIN RESOURCE SHARING(CORS) PREFILIGHT
    if($_SERVER["REQUEST_METHOD"] == "OPTIONS"){
        http_response_code(200);
        exit;
    }

    // IF METHOD IS PUT
    if ($_SERVER["REQUEST_METHOD"] == "PUT"){
        $headers = apache_request_headers();
        $check_auth = new CheckAuth($conn, $headers);
        $auth = $check_auth->isValid();

        if($auth['success'] == 1){
            // Checks if all the fields is set and filled up
            if (
                !isset($data->teacher_id)
                || !isset($data->service_id)
                || !isset($data->starting_date)
                || !isset($data->ending_date)
            ){
                $fields = ['fields' => ['teacher_id', 'event_name', 'starting_date', 'ending_date', 'level_of_event', 'credit_point']];
                http_response_code(400);
                $returnData = msg(0,400,'Please Fill in all Required Fields!',$fields);
            }

            else{
                try{
                    $days = calc_date($data->starting_date, $data->ending_date);
                    $obj->teacher_id = $data->teacher_id;
                    $obj->service_id = $data->service_id;
                    $obj->credit_point = $days;

                    $result = $obj->updateCredit();

                    if($result == 1){
                        http_response_code(201);
                        $returnData = msg(1, 201, 'Credit Points Succesfuly Updated');
                    }
                    else{
                        http_response_code(500);
                        $returnData = msg(0, 500, 'something went wrong');
                    }

                }
                catch (PDOException $e) {
                    http_response_code(500);
                    $returnData = msg(0, 500, $e->getMessage());
                }
            }
        }
        else{
            http_response_code(401);
            $returnData = msg(0, 401, 'Authentication Failed');
        }
    }
    else{
        http_response_code(405);
        $returnData = msg(0, 405, 'Method not allowed!');
    }
    echo json_encode($returnData);


?>
