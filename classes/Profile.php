<?php
    class Profile{
        protected $db;

        public $profile_dir;
        public $acc_id;

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
    }
?>
