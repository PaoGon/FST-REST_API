<?php
    Class Admin{
        protected $db;

        public $acc_id;
        public $full_name;
        public $email;
        public $password;
        public $gender;
        public $is_admin;
        public $position;

        
        public function __construct($db){
            $this->db = $db;
        }

        public function get_accounts(){
            $query_teacher = "
                    SELECT
                        teachers.acc_id AS teacher_id,
                        full_name,
                        position,
                        total_credits
                        FROM accounts
                    INNER JOIN teachers
                        ON teachers.acc_id = accounts.acc_id;
            ";

            $stmt = $this->db->prepare($query_teacher);
            $stmt->execute();
            return $stmt;
        }

        // helper function for create_acc()
        public function create_teacher(){
            $get_acc_id = "SELECT acc_id FROM accounts WHERE email=:email";
            $query_stmt = $this->db->prepare($get_acc_id);
            $query_stmt->bindValue(':email', $this->email,PDO::PARAM_STR);
            $query_stmt->execute();
            $acc_row = $query_stmt->fetch(PDO::FETCH_ASSOC);

            $teacher_id = $acc_row['acc_id'];

            $create_teacher = "INSERT INTO teachers(acc_id, position) VALUES(:acc_id, :position)";
            $insert_teacher = $this->db->prepare($create_teacher);

            $insert_teacher->bindValue(':acc_id', $teacher_id, PDO::PARAM_INT);
            $insert_teacher->bindValue(':position', $this->position, PDO::PARAM_STR);
            $insert_teacher->execute();

            if($insert_teacher->rowCount() > 0) return 1; 

            return 0;
        }

        // Create account
        public function create_acc(){
            $create_query = "
                INSERT INTO accounts(full_name, email, password, gender, is_admin) 
                VALUES(:full_name,:email,:password, :gender, :is_admin)
            ";

            $create_stmt = $this->db->prepare($create_query);

            // DATA BINDING
            $create_stmt->bindValue(':full_name', htmlspecialchars(strip_tags($this->full_name)), PDO::PARAM_STR);
            $create_stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $create_stmt->bindValue(':password', password_hash($this->password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $create_stmt->bindValue(':gender', $this->gender, PDO::PARAM_STR);
            $create_stmt->bindValue(':is_admin', $this->is_admin, PDO::PARAM_INT);

            $create_stmt->execute();
            
            if($create_stmt->rowCount() > 0) return 1;

            return 0;
        }

        // Delete account
        public function del_acc(){
            $del_query = "
                DELETE FROM accounts
                WHERE acc_id = :acc_id
            ";

            $del_stmt = $this->db->prepare($del_query);
            $del_stmt->bindValue(':acc_id', $this->acc_id, PDO::PARAM_INT);
            
            $del_stmt->execute();

            if($del_stmt->rowCount() > 0) return 1;

            return 0;

        }
    }
?>
