CREATE TABLE users (
	user_id INT (11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    user_pwd LONGTEXT NOT NULL,
    user_type ENUM('client', 'legal_practitioner' ) NOT NULL,
    created_on DATETIME NOT NULL DEFAULT CURRENT_TIME,
    PRIMARY KEY (id)
);


CREATE TABLE practitioners (
    practitioner_id INT AUTO_INCREMENT,
    user_id INT UNIQUE,
    username VARCHAR(30) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    profession VARCHAR(100) NOT NULL,
    PRIMARY KEY (practitioner_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


CREATE TABLE profiles (
    profiles_id INT NOT NULL AUTO_INCREMENT,
    user_id INT UNIQUE,
    username VARCHAR(30) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    profession VARCHAR(100) NOT NULL,
    firm VARCHAR(100) NOT NULL,
    specializations VARCHAR(1000) NOT NULL,
    experience_years INT NOT NULL,
    phone_number VARCHAR(20) NOT NULL, 
    working_hours_start TIME NOT NULL,
    working_hours_end TIME NOT NULL,
    physical_address VARCHAR(100) NOT NULL,
    profile_about TEXT NOT NULL,
    PRIMARY KEY (profiles_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


CREATE TABLE practitioner_specializations (
    specialization_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    practitioner_id INT,
    specialization VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (practitioner_id) REFERENCES practitioners(practitioner_id)
);


CREATE TABLE bookings (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  practitioner_id INT(11) NOT NULL,
  date TEXT NOT NULL,
  time TEXT NOT NULL,
  client_name TEXT NOT NULL,
  client_email TEXT NOT NULL,
  client_phone TEXT NOT NULL,
  service_type TEXT NOT NULL,
  comments TEXT NOT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY practitioner_id (practitioner_id),
  CONSTRAINT bookings_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id),
  CONSTRAINT bookings_ibfk_2 FOREIGN KEY (practitioner_id) REFERENCES practitioners (practitioner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DELIMITER //

CREATE TRIGGER create_practitioner_after_insert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    IF NEW.user_type = 'legal_practitioner' THEN
        INSERT INTO practitioners (user_id, username, user_email)
        VALUES (NEW.user_id, NEW.username, NEW.user_email);
    END IF;
END;
//

DELIMITER ;

