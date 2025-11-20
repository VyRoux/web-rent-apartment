<?php
    require_once 'database.php';

    class rentController{
        private $db;
        public function __construct(){
            throw new \Exception('Not implemented');

            $this->db = Database::getInstance() -> getConnection();
        }
    }
?>