<?php
    class Profile{
        protected $db;

        public $profile_dir;
        public $acc_id;
        public $full_name;
        public $gender;
        public $position;
        public $pass;

        public function __construct($db){
            $this->db = $db;
        }


        // Update Credit Points
        public function updateProfile(){
            $update_query = "
                UPDATE accounts
                SET 
                    profile_dir = :profile_dir
                WHERE acc_id = :acc_id
            ";

            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindValue(':acc_id', $this->acc_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':profile_dir', $this->profile_dir, PDO::PARAM_STR);
            
            $update_stmt->execute();

            if($update_stmt->rowCount() > 0) return 1;

            return 0;
        }

        public function updateAccInfo(){
            $update_query = "
                UPDATE accounts
                SET 
                    full_name = :full_name,
                    gender = :gender
                WHERE acc_id = :acc_id
            ";

            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindValue(':acc_id', $this->acc_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':full_name', $this->full_name, PDO::PARAM_STR);
            $update_stmt->bindValue(':gender', $this->gender, PDO::PARAM_STR);
            
            $update_stmt->execute();

            if($update_stmt->rowCount() > 0) return 1;

            return 0;
        }

        public function changePass(){
            $update_query = "
                UPDATE accounts
                SET 
                    password = :password
                WHERE acc_id = :acc_id
            ";
            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindValue(':acc_id', $this->acc_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':password', password_hash($this->pass, PASSWORD_DEFAULT), PDO::PARAM_STR);

            
            $update_stmt->execute();

            if($update_stmt->rowCount() > 0) return 1;

            return 0;

        }
        
        public function getCurrPass(){
            $update_query = "SELECT password FROM accounts WHERE acc_id = :acc_id";

            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindValue(':acc_id', $this->acc_id, PDO::PARAM_INT);
            
            $update_stmt->execute();

            if($update_stmt->rowCount() > 0) {
                $row = $update_stmt->fetch(PDO::FETCH_ASSOC);
                return $row['password'];
            }

            return 0;
        }
    }
?>
