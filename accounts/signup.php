<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require __DIR__ . '/../config/Database.php';
    require __DIR__ . '/../accounts/CheckAuth.php';
    require __DIR__ . '/../classes/Admin.php';
    require __DIR__ . '/../classes/Service.php';
    require __DIR__ . '/../helpers/msg.php';

    $database = new Database();
    $conn = $database->getConnection();
    $obj = new Admin($conn);
    $service = new Service($conn);


    // DATA FORM REQUEST
    $data = json_decode(file_get_contents("php://input"));
    $returnData = [];

    // For CROSS-ORGIN RESOURCE SHARING(CORS) PREFILIGHT
    if ($_SERVER["REQUEST_METHOD"] == "OPTIONS"){
        http_response_code(200);
        exit;
    }

    // IF METHOD IS POST
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $headers = apache_request_headers();
        $check_auth = new CheckAuth($conn, $headers);
        $auth = $check_auth->isValid();

        if($auth['success'] == 1){
            if (
                !isset($data->full_name)
                || !isset($data->email)
                || !isset($data->password)
                || !isset($data->gender)
                || !isset($data->is_admin)
                || empty(trim($data->full_name))
                || empty(trim($data->email))
                || empty(trim($data->password))
                || empty(trim($data->gender))
            ){
                $fields = ['fields' => ['full_name', 'email', 'password', 'gender', 'is_admin']];
                $test = [$data];
                http_response_code(400);
                $returnData = msg(0,400,'Please Fill in all Required Fields!',$test);
            }
            else{

                $full_name = trim($data->full_name);
                $email = trim($data->email);
                $password = trim($data->password);
                $gender = trim($data->gender);
                $is_admin = $data->is_admin;

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    http_response_code(401);
                    $returnData = msg(0,401,'Invalid Email Address!');
                } 

                elseif (strlen($password) < 8){
                    http_response_code(401);
                    $returnData = msg(0, 401, 'Your password must be at least 8 characters long!');
                } 

                elseif (strlen($full_name) < 3){
                    http_response_code(401);
                    $returnData = msg(0, 401, 'Your name must be at least 3 characters long!');
                }

                else{
                    try {
                        $check_email = "SELECT email FROM accounts WHERE email=:email";
                        $check_email_stmt = $conn->prepare($check_email);
                        $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                        $check_email_stmt->execute();

                        if ($check_email_stmt->rowCount()){
                            http_response_code(400);
                            $returnData = msg(0, 400, 'This E-mail already in use!');

                        } 
                        else{
                            $obj->full_name = $full_name;
                            $obj->email = $email;
                            $obj->password = $password;
                            $obj->gender = $gender;
                            $obj->is_admin = $is_admin;

                            $result = $obj->create_acc();
                            $acc_id = $service->getId($email);
                            if($result == 1){
                                if($is_admin == 0){
                                    $obj->position = $data->position;
                                    $obj->create_teacher($acc_id);
                                }

                                $path = __DIR__.'/../storage/'.$acc_id;

                                $old = umask(0);
                                mkdir($path, 0777);
                                umask($old);

                                http_response_code(201);
                                $returnData = msg(1, 201, 'Account created successfully', array("acc_id"=> $acc_id));
                            }
                            else{
                                http_response_code(500);
                                $returnData = msg(0, 500, 'somethingg went wrong');

                            }

                        }
                    } catch (PDOException $e) {
                        http_response_code(500);
                        $returnData = msg(0, 500, [$e->getMessage()]);
                    }
                } 
            }
        }
        else{
            http_response_code(401);
            $returnData = msg(0, 401, 'Authentication Failed', $auth['success']);
        }
    }
    // IF INVALID METHOD
    else{
        http_response_code(405);
        $returnData = msg(0, 405, 'Method not allowed!');
    } 

    echo json_encode($returnData);
?>
