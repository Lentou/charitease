<?php

class Database {

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';

    protected $conn;

    public function __construct(private string $db = 'dbcharitease') {
        if (!isset($this->conn)) {
            $this->conn = new mysqli($this->host, $this->user, $this->pass);

            if ($this->db == 'charitease') {
                $this->conn->select_db($this->db);
            } else {
                $this->loader();
            }

            if (!$this->conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        }
    }

    public function connect() : mysqli {
        return $this->conn;
    }

    public function query(string $query) {
        return $this->connect()->query($query);
    }

    public function lastInsertId() {
        return $this->connect()->insert_id;
    }

    public function loader() {

        $database = $this->db;
        $sql_db = "CREATE DATABASE IF NOT EXISTS $database";

        // account_type ENUM('donor, 'charity', 'admin'),
        // gender ENUM('male', 'female')
        $sql_users = "CREATE TABLE IF NOT EXISTS tblusers (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE,
            password VARCHAR(255),
            account_type CHAR(2),
            is_verified TINYINT(1),
            verification_pin VARCHAR(255),
            bday DATE,
            gender CHAR(1),
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            date_approved DATE NULL
        )";

        // client_user_type ENUM('individual', 'organization')
        // client_org_type SET('environment', 'health', 'religious', 'education', NULL)
        $sql_clients = "CREATE TABLE IF NOT EXISTS tblclients (
            client_id INT PRIMARY KEY,
            client_name VARCHAR(255),
            client_phone VARCHAR(20),
            client_contact_name VARCHAR(255),
            client_address TEXT,
            client_bio TEXT NULL,
            client_lat FLOAT,
            client_lng FLOAT,
            client_user_type CHAR(1),
            client_org_type CHAR(2) NULL,
            date_founded DATE NULL,
            is_approved TINYINT(1),
            FOREIGN KEY (client_id) REFERENCES tblusers(user_id)
        )";

        // initiated_by ENUM('charity', 'donor', 'admin')
        $sql_chats = "CREATE TABLE IF NOT EXISTS tblchats (
            convo_id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT,
            receiver_id INT,
            initiated_by CHAR(2),
            message TEXT,
            is_read TINYINT(1),
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES tblclients(client_id),
            FOREIGN KEY (receiver_id) REFERENCES tblclients(client_id)
        )";

        $sql_ratings = "CREATE TABLE IF NOT EXISTS tblratings (
            rating_id INT AUTO_INCREMENT PRIMARY KEY,
            donor_id INT,
            org_id INT,
            rating INT,
            review TEXT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (donor_id) REFERENCES tblclients(client_id),
            FOREIGN KEY (org_id) REFERENCES tblclients(client_id)
        )";

        // event_type ENUM('announcement', 'event')
        //  event_status ENUM('status1', 'status2')
        $sql_events = "CREATE TABLE IF NOT EXISTS tblevents (
            event_id INT AUTO_INCREMENT PRIMARY KEY,
            org_id INT,
            event_title VARCHAR(255),
            event_type CHAR(1),
            event_description TEXT,
            event_start_date DATE,
            event_end_date DATE,
            event_status VARCHAR(255),
            post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            is_approved TINYINT(1),
            FOREIGN KEY (org_id) REFERENCES tblclients(client_id)
        )";

        $sql_subevents = "CREATE TABLE IF NOT EXISTS tblsubevents (
            sub_event_id INT AUTO_INCREMENT PRIMARY KEY,
            event_id INT,
            sub_event_title VARCHAR(255),
            sub_event_description TEXT,
            post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (event_id) REFERENCES tblevents(event_id)
        )";

        $sql_collections = "CREATE TABLE IF NOT EXISTS tblcollections (
            collection_id INT AUTO_INCREMENT PRIMARY KEY,
            event_id INT,
            current_inkind DECIMAL(10, 2),
            target_inkind DECIMAL(10, 2),
            current_funds DECIMAL(10, 2),
            target_funds DECIMAL(10, 2),
            FOREIGN KEY (event_id) REFERENCES tblevents(event_id)
        )";

        // donation_type ENUM('inkind', 'monetary')
        // donation_status ENUM('pending', 'approved', 'rejected')
        $sql_donations = "CREATE TABLE IF NOT EXISTS tbldonations (
            donation_id INT AUTO_INCREMENT PRIMARY KEY,
            donor_id INT,
            org_id INT,
            event_id INT,
            donation_type CHAR(1),
            donation_amount DECIMAL(10, 2),
            donation_name VARCHAR(255),
            donation_date DATE,
            donation_status CHAR(1),
            FOREIGN KEY (donor_id) REFERENCES tblclients(client_id),
            FOREIGN KEY (org_id) REFERENCES tblclients(client_id),
            FOREIGN KEY (event_id) REFERENCES tblevents(event_id)
        )";

        // old columns
        // permit_type ENUM('permit', 'valid_ids', 'blog', 'event', 'sub_event');
        // category ENUM('donor_permit', 'org_permit', 'event_image', 'sub_event_image', 'donation_image');

        // category ENUM('profile', 'permit', 'valid_ids', 'event_image', 'sub_event_image', 'donation_image');
        $sql_images = "CREATE TABLE IF NOT EXISTS tblimages (
            image_id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(255),
            image_name VARCHAR(255),
            image_data LONGBLOB,
            client_id INT NULL,
            event_id INT NULL,
            sub_event_id INT NULL,
            FOREIGN KEY (client_id) REFERENCES tblclients(client_id),
            FOREIGN KEY (event_id) REFERENCES tblevents(event_id),
            FOREIGN KEY (sub_event_id) REFERENCES tblsubevents(sub_event_id)
        )";

        $this->query($sql_db);

        $this->connect()->select_db($database);

        $this->query($sql_users);
        $this->query($sql_clients);
        $this->query($sql_chats);
        $this->query($sql_ratings);
        $this->query($sql_events);
        $this->query($sql_subevents);
        $this->query($sql_collections);
        $this->query($sql_donations);
        $this->query($sql_images);
        
    }

}
?>

