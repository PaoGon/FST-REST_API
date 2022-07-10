<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: PUT, OPTIONS");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require __DIR__ . '/../config/Database.php';
    require __DIR__ . '/../accounts/CheckAuth.php';
    require __DIR__ . '/../classes/Profile.php';
    require __DIR__ . '/../helpers/msg.php';

    $database = new Database();
    $conn = $database->getConnection();
    $obj = new Profile($conn);

    // DATA FORM REQUEST
    $data = json_decode(file_get_contents("php://input"));
    $returnData = [];

    // For CROSS-ORGIN RESOURCE SHARING(CORS) PREFILIGHT
    if($_SERVER["REQUEST_METHOD"] == "OPTIONS"){
        http_response_code(200);
        exit;
    }

    // IF METHOD IS POST
    if ($_SERVER["REQUEST_METHOD"] == "PUT"){
        $headers = apache_request_headers();
        $check_auth = new CheckAuth($conn, $headers);
        $auth = $check_auth->isValid();

        if($auth['success'] == 1){

            // Checks if all the fields is set and filled up
            if (
                !isset($data->acc_id)
                || !isset($data->full_name)
                || !isset($data->gender)
            )
            {
                http_response_code(400);
                $returnData = msg(0,400,'Please Fill in all Required Fields!');
            }
            else{
                try{
                    $obj->acc_id = $data->acc_id;
                    $obj->full_name = $data->full_name;
                    $obj->gender = $data->gender;

                    $result = $obj->updateAccInfo();

                    if($result == 1){
                        http_response_code(201);
                        $returnData = msg( 0, 201, "Updated Successfully");
                    }
                    else{
                        http_response_code(500);
                        $returnData = msg(0, 500, 'something went wrong', array($result));
                        
                    }
                }
                catch (PDOException $e) {
                    http_response_code(500);
                    $returnData = msg(0, 500, [$e->getMessage()]);
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
