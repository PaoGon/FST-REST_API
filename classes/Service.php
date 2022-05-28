<?php
    class Post{
        protected $db;

        public $service_id;
        public $teacher_id;
        public $event_name;
        public $event_date;
        public $level_of_event;
        public $credit_point;

        public function __construct($db){
            $this->db = $db;
        }

        // Get all posts in descending order
        public function getServices(){
            $query_posts = "
                SELECT 
                    services.service_id, 
                    teachers.acc_id,
                    event_name, 
                    event_date, 
                    level_of_event, 
                    credit_point
                FROM teachers
                INNER JOIN services
                    ON services.teacher_id = teachers.acc_id
                ORDER BY created_at DESC
            ";
            $stmt = $this->db->prepare($query_posts);
            $stmt->execute();
            return $stmt;
        }


        // Get all the post of a certain user
        public function getOwnPosts(){
            $query_service = "
                SELECT service_id, teacher_id, event_name, event_date, level_of_event, credit_point
                FROM services
                WHERE teacher_id = :teacher_id 
                ORDER BY created_at DESC;
            ";
            $query_stmt = $this->db->prepare($quert_service);
            $query_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            $query_stmt->execute();

            return $query_stmt;
        }

        // Create post
        public function createService(){
            $create_query = "
                INSERT into services(teacher_id, event_name, event_date, level_of_event, credit_point) 
                VALUES(:teacher_id, :event_name, :event_date, :level_of_event, :credit_point)
            ";

            $create_stmt = $this->db->prepare($create_query);
            $create_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            $create_stmt->bindValue(':event_name', $this->event_name, PDO::PARAM_STR);
            $create_stmt->bindValue(':event_date', $this->event_date, PDO::PARAM_STR);
            $create_stmt->bindValue(':level_of_event', $this->level_of_event, PDO::PARAM_STR);
            $create_stmt->bindValue(':credit_point', $this->credit_point, PDO::PARAM_INT);
            
            $create_stmt->execute();

            if($create_stmt->rowCount() > 0){
                return 1;
            }
            else{
                return 0;
            }
        }

        // Update post
        public function updatePost(){
            $update_query = "
                UPDATE services
                SET 
                    event_name = :event_name,
                    event_date = :event_date,
                    level_of_event = :level_of_event,
                    credit_point = :credit_point
                WHERE teacher_id = :teacher_id AND service_id = :service_id 
            ";

            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            $update_stmt->bindValue(':event_name', $this->event_name, PDO::PARAM_STR);
            $update_stmt->bindValue(':event_date', $this->event_date, PDO::PARAM_STR);
            $update_stmt->bindValue(':level_of_event', $this->level_of_event, PDO::PARAM_STR);
            $update_stmt->bindValue(':credit_point', $this->credit_point, PDO::PARAM_INT);
            
            $update_stmt->execute();
        }

        // Delete post
        public function deletePost(){
            $update_query = "
                DELETE FROM service
                WHERE teacher_id = :teacher_id AND service_id = :service_id
            ";

            $delete_stmt = $this->db->prepare($update_query);
            $delete_stmt->bindValue(':service_id', $this->service_id, PDO::PARAM_INT);
            $delete_stmt->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
            
            $delete_stmt->execute();
        }
    }
?>
