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
    practitioner_id INT UNIQUE,
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
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (practitioner_id) REFERENCES practitioners(practitioner_id)
);



CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    practitioner_id INT,
    date DATE,
    time TIME,
    client_name VARCHAR(100),
    client_email VARCHAR(100),
    client_phone VARCHAR(20),
    service_type VARCHAR(100),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    appointment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (practitioner_id) REFERENCES practitioners(practitioner_id)
);



-- Trigger to create an entry into the practitioners table after a new user(i.e. legal practitioner) creates an account
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




-- Trigger to insert and update practitioners table Full name and profession then the user creates and updates their profile.
DELIMITER //

CREATE TRIGGER update_practitioner_after_insert
AFTER INSERT ON profiles
FOR EACH ROW
BEGIN
    UPDATE practitioners
    SET full_name = NEW.full_name,
        profession = NEW.profession
    WHERE practitioner_id = NEW.practitioner_id;
END;
//

CREATE TRIGGER update_practitioner_after_update
AFTER UPDATE ON profiles
FOR EACH ROW
BEGIN
    UPDATE practitioners
    SET full_name = NEW.full_name,
        profession = NEW.profession
    WHERE practitioner_id = NEW.practitioner_id;
END;
//

DELIMITER ;



ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0;


CREATE TABLE documents (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    document_name VARCHAR(255) NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    document_size INT NOT NULL,
    document_data LONGBLOB NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



