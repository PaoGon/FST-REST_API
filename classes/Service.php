<?php
    class Service{
        protected $db;

        public $service_id;
        public $teacher_id;
        public $event_name;
        public $starting_date;
        public $ending_date;
        public $level_of_event;
        public $credit_point;
        public $sponsor;
        public $venue;

        public function __construct($db){
            $this->db = $db;
        }

        public function getId($email){
            $fetch_user_by_email = "SELECT acc_id FROM accounts WHERE email=:email";
            $query_stmt = $this->db->prepare($fetch_user_by_email);
            $query_stmt->bindValue(':email', $email, PDO::PARAM_INT);
            $query_stmt->execute();

            $res = $query_stmt->fetch(PDO::FETCH_ASSOC);
            $acc_id = $res['acc_id'];

            if ($query_stmt->rowCount()){
                return $acc_id;
            } 
            else{
                return false;
            }
        }

        // Get all services in descending order
        public function getServices(){
            $query_posts = "
                SELECT 
                    services.service_id, 
                    teachers.acc_id,
                    event_name, 
                    starting_date, 
                    ending_date, 
                    venue, 
                    level_of_event, 
                    credit_point,
                    sponsor,
                    service_dir
                FROM teachers
                INNER JOIN services
                    ON services.teacher_id = teachers.acc_id
                ORDER BY created_at DESC
            ";
            $stmt = $this->db->prepare($query_posts);
            $stmt->execute();
            return $stmt;
        }


        // Get all the services of a certain user
        public function getOwnServices(){
            $query_service = "
                SELECT 
                    service_id, 
                    teacher_id, 
                    event_name, 
                    starting_date, 
                    ending_date, 
                    venue, 
                    level_of_event, 
                    credit_point, 
                    sponsor,
                    service_dir
                FROM services
                WHERE teacher_id = :teacher_id 
                ORDER BY created_at DESC;
            ";
            $query_stmt = $this->db->prepare($query_service);
            $query_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            $query_stmt->execute();

            return $query_stmt;
        }

        public function getOneService($id, $name){
            $get_query = "
                SELECT service_id, service_dir from services 
                WHERE teacher_id=:teacher_id AND event_name=:event_name";
            
            $get_stmt = $this->db->prepare($get_query);
            $get_stmt->bindValue(':teacher_id', $id, PDO::PARAM_INT);
            $get_stmt->bindValue(':event_name', $name, PDO::PARAM_STR);

            $get_stmt->execute();

            if($get_stmt->rowCount() > 0){
                return array("status"=> 1, "data" => $get_stmt->fetch(PDO::FETCH_ASSOC));
            } 

            return array("status" => 0);

        }

        // Create service
        public function createService(){
            $create_query = "
                INSERT INTO services(teacher_id, event_name, starting_date, ending_date, venue, sponsor, level_of_event, credit_point) 
                VALUES(:teacher_id, :event_name, :starting_date, :ending_date, :venue, :sponsor, :level_of_event, :credit_point)
            ";

            $create_stmt = $this->db->prepare($create_query);
            $create_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            $create_stmt->bindValue(':event_name', $this->event_name, PDO::PARAM_STR);
            $create_stmt->bindValue(':starting_date', $this->starting_date, PDO::PARAM_STR);
            $create_stmt->bindValue(':ending_date', $this->ending_date, PDO::PARAM_STR);
            $create_stmt->bindValue(':sponsor', $this->sponsor, PDO::PARAM_STR);
            $create_stmt->bindValue(':venue', $this->venue, PDO::PARAM_STR);
            $create_stmt->bindValue(':level_of_event', $this->level_of_event, PDO::PARAM_STR);
            $create_stmt->bindValue(':credit_point', $this->credit_point, PDO::PARAM_INT);
            
            $create_stmt->execute();

            //$created_data = getOneService($this->teacher_id, $this->event_name);

            if($create_stmt->rowCount() > 0) return 1;

            return 0;
        }

        // Update service
        public function updateCredit(){
            $update_query = "
                UPDATE services
                SET 
                    credit_point = :credit_point
                WHERE teacher_id = :teacher_id AND service_id = :service_id 
            ";

            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':service_id', $this->service_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':credit_point', $this->credit_point, PDO::PARAM_INT);
            
            $update_stmt->execute();

            if($update_stmt->rowCount() > 0) return 1;

            return 0;
        }

        // Update service
        public function updateService(){
            $update_query = "
                UPDATE services
                SET 
                    event_name = :event_name,
                    starting_date = :starting_date,
                    ending_date = :ending_date,
                    venue = :venue,
                    level_of_event = :level_of_event,
                    credit_point = :credit_point,
                    sponsor = :sponsor
                WHERE teacher_id = :teacher_id AND service_id = :service_id 
            ";

            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':service_id', $this->service_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':event_name', $this->event_name, PDO::PARAM_STR);
            $update_stmt->bindValue(':starting_date', $this->starting_date, PDO::PARAM_STR);
            $update_stmt->bindValue(':ending_date', $this->ending_date, PDO::PARAM_STR);
            $update_stmt->bindValue(':venue', $this->venue, PDO::PARAM_STR);
            $update_stmt->bindValue(':level_of_event', $this->level_of_event, PDO::PARAM_STR);
            $update_stmt->bindValue(':credit_point', $this->credit_point, PDO::PARAM_INT);
            $update_stmt->bindValue(':sponsor', $this->sponsor, PDO::PARAM_INT);
            
            $update_stmt->execute();

            if($update_stmt->rowCount() > 0) return 1;

            return 0;
        }

        // Delete service
        public function deleteService(){
            $del_query = "
                DELETE FROM services
                WHERE teacher_id = :teacher_id AND service_id = :service_id
            ";

            $del_stmt = $this->db->prepare($del_query);
            $del_stmt->bindValue(':service_id', $this->service_id, PDO::PARAM_INT);
            $del_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            
            $del_stmt->execute();

            if($del_stmt->rowCount() > 0) return 1;

            return 0;
            
        }
    }
?>
