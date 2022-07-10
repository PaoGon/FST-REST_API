<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
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
    $returnData = [];

    // For CROSS-ORGIN RESOURCE SHARING(CORS) PREFILIGHT
    if($_SERVER["REQUEST_METHOD"] == "OPTIONS"){
        http_response_code(200);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $headers = apache_request_headers();
        $check_auth = new CheckAuth($conn, $headers);
        $auth = $check_auth->isValid();

        if($auth['success'] == 1){
            $file_name = $_FILES['file']['name'];
            $path = $_FILES['file']['tmp_name'];
            $error = $_FILES['file']['error'];

            if($error != 1){
                $acc_id = $_POST['acc_id'];
                $base_url = "http://192.168.254.101/FST-REST_API/storage/";
                $profile_dir = __DIR__ . "/../storage/".$acc_id.'/profile/';  
                
                $valid_extension = array('png', 'jpg', 'jpeg');
                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // get image file extension

                //$new_filename = "curr_porfile".".".$file_extension;

                if(in_array($file_extension, $valid_extension)){                        // check if the file extension is valid
                    try{
                        $upload_status = move_uploaded_file($path, $profile_dir.$file_name);
                        if($upload_status){ // move uploaded file to accounts directory
                            $obj->acc_id = intval($acc_id);
                            $obj->profile_dir = $base_url.$acc_id.'/profile/'.$file_name;

                            $result = $obj->updateProfile();

                            if($result == 1){
                                http_response_code(200);
                                $returnData = msg(0, 200, 'Profile Uploaded Successfully');
                            }
                            else{
                                http_response_code(400);
                                $returnData = msg(0, 400, 'Bad Request');
                            }
                        }
                        else{
                            http_response_code(400);
                            $returnData = msg(0, 400, 'Uploading Failed');
                        }
                    }
                    catch(Exception $e){
                        http_response_code(500);
                        $returnData = msg(0, 500, [$e->getMessage]);
                    }
                }
                else{
                    http_response_code(400);
                    $returnData = msg(0,400, 'File type not allowed',array("type"=>$profile_dir));
                }
            }
            else{
                http_response_code(400);
                $returnData = msg(0,400,'File Upload Failed, Please upload again', $_FILES);
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
