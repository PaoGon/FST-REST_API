<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Content-Type: application/json");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require __DIR__ . '/../config/Database.php';
    require_once __DIR__ . '/../classes/JwtHandler.php';
    require __DIR__ . '/../helpers/msg.php';

    $database = new Database();
    $conn = $database->getConnection();

    $data = json_decode(file_get_contents("php://input"));
    $returnData = [];

    // For CROSS-ORGIN RESOURCE SHARING(CORS) PREFILIGHT
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // IF REQUEST METHOD IS POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // CHECKING EMPTY FIELDS
        if(!isset($data->email) 
            || !isset($data->password)
            || empty(trim($data->email))
            || empty(trim($data->password))
        ){
            $fields = ['fields' => ['email','password']];
            http_response_code(400);
            $returnData = msg(0,400,'Please Fill in all Required Fields!',$fields);
        }
        else{

            $email = trim($data->email);
            $password = trim($data->password);

            // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                http_response_code(401);
                $returnData = msg(0,401,'Invalid Email Address!');
            }
            // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
            else{

                try{

                    $fetch_user_by_email = "SELECT * FROM accounts WHERE email=:email";
                    $query_stmt = $conn->prepare($fetch_user_by_email);
                    $query_stmt->bindValue(':email', $email,PDO::PARAM_STR);
                    $query_stmt->execute();

                    // IF THE USER IS FOUNDED BY EMAIL
                    if($query_stmt->rowCount()){
                        $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                        $check_password = password_verify($password, $row['password']);

                        // VERIFYING THE PASSWORD 
                        // IF PASSWORD IS CORRECT THEN Create THE LOGIN TOKEN
                        if($check_password){
                            $jwt = new JwtHandler();
                            $token = $jwt->jwtEncodeData(
                                'http://faculty_service.local/accounts', 
                                'http://localhost:3000',
                                array('acc_id'=>$row['acc_id'])
                            );

                            http_response_code(200);
                            $returnData = [
                                'success' => 1,
                                'message' => 'You have successfully logged in.',
                                'is_admin' => $row['is_admin'],
                                'profile_dir' => $row['profile_dir'],
                                'token' => $token
                            ];
                        }

                        // IF PASSWORD IS INCORRECT
                        else{
                            http_response_code(401);
                            $returnData = msg(0,401,'Invalid Email or Password!');
                        }
                    }

                    // IF THE USER EMAIL NOT FOUND THEN SHOW THE FOLLOWING ERROR 
                    else{
                        $returnData = msg(0,422,'Invalid Email or Password!');
                    }
                }
                catch(PDOException $e){
                    http_response_code(500);
                    $returnData = msg(0,500,$e->getMessage());
                }
            }
        }
    }
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else{
        http_response_code(405);
        $returnData = msg(0, 405, 'Method Not Allowed!');
    }
    echo json_encode($returnData);
?>
