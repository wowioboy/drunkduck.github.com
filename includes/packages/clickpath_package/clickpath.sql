CREATE TABLE click_path_file_to_file
(
  subdomain   VARCHAR(20),
  source_file VARCHAR(255),
  target_file VARCHAR(255),
  counter     INT(11) NOT NULL DEFAULT '1',
  ymd_date    INT(8) NOT NULL,
  logged_in   TINYINT(1) NOT NULL,

  PRIMARY KEY (subdomain, source_file, target_file, ymd_date, logged_in),
  INDEX(source_file),
  INDEX(target_file),
  INDEX(ymd_date)
);



CREATE TABLE click_path_folder_to_folder
(
  subdomain     VARCHAR(20),
  source_folder VARCHAR(255),
  target_folder VARCHAR(255),
  counter       INT(11) NOT NULL DEFAULT '1',
  ymd_date      INT(8) NOT NULL,
  logged_in     TINYINT(1) NOT NULL,

  PRIMARY KEY (subdomain, source_folder, target_folder, ymd_date, logged_in),
  INDEX(source_folder),
  INDEX(target_folder),
  INDEX(ymd_date)
);


CREATE TABLE click_path_file_to_folder
(
  subdomain     VARCHAR(20),
  source_file   VARCHAR(255),
  target_folder VARCHAR(255),
  counter       INT(11) NOT NULL DEFAULT '1',
  ymd_date      INT(8) NOT NULL,
  logged_in     TINYINT(1) NOT NULL,

  PRIMARY KEY (subdomain, source_file, target_folder, ymd_date, logged_in),
  INDEX(source_file),
  INDEX(target_folder),
  INDEX(ymd_date)
);

CREATE TABLE click_path_folder_to_file
(
  subdomain     VARCHAR(20),
  source_folder   VARCHAR(255),
  target_file VARCHAR(255),
  counter       INT(11) NOT NULL DEFAULT '1',
  ymd_date      INT(8) NOT NULL,
  logged_in     TINYINT(1) NOT NULL,

  PRIMARY KEY (subdomain, source_folder, target_file, ymd_date, logged_in),
  INDEX(source_folder),
  INDEX(target_file),
  INDEX(ymd_date)
);