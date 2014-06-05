-- Database Creation
DROP DATABASE store;
CREATE DATABASE store;
USE store;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE cart_tbl (
	cartID TINYINT(3) NOT NULL UNIQUE  AUTO_INCREMENT PRIMARY KEY,
	memberID SMALLINT(5) UNSIGNED NOT NULL UNIQUE,
	FOREIGN KEY (memberID) REFERENCES member_tbl(memberID)
);
CREATE TABLE category_tbl (
	categoriesID TINYINT(3) NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	parentCategoryID TINYINT(3) NOT NULL,
	subCategoryID TINYINT(3) NOT NULL UNIQUE,
	FOREIGN KEY (parentCategoryID) REFERENCES parent_category_tbl(parentCategoryID),
	FOREIGN KEY (subCategoryID) REFERENCES sub_category_tbl(subCategoryID)
);
CREATE TABLE manager_tbl (
	managerID SMALLINT(5) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	promote ENUM('T', 'F') NOT NULL,
	FOREIGN KEY (managerID) REFERENCES staff_tbl(staffID)
);
CREATE TABLE member_tbl (
	memberID SMALLINT(5) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(4) NOT NULL,
	firstName VARCHAR(25) NOT NULL,
	lastName VARCHAR(25) NOT NULL,
	gender ENUM('F', 'M') NOT NULL,
	email VARCHAR(25) NOT NULL UNIQUE,
	password VARCHAR(32) NOT NULL,
	DOB DATE NOT NULL,
	contactNo VARCHAR(8) NOT NULL,
	userType ENUM('3') NOT NULL DEFAULT '3'
);
CREATE TABLE parent_category_tbl (
	parentCategoryID TINYINT(3) NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	parentCategory VARCHAR(25) NOT NULL,
	description VARCHAR(255) NOT NULL
);
CREATE TABLE product_cart_tbl (
	pCartID SMALLINT(5) UNSIGNED NOT NULL UNIQUE  AUTO_INCREMENT PRIMARY KEY,
	cartID TINYINT(3) NOT NULL,
	productID SMALLINT(5) UNSIGNED NOT NULL,
	FOREIGN KEY (cartID) REFERENCES cart_tbl(cartID),
	FOREIGN KEY (productID) REFERENCES product_tbl(productID)
);
CREATE TABLE product_tbl (
	productID SMALLINT(5) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	product VARCHAR(50) NOT NULL,
	image VARCHAR(25) NOT NULL,
	details VARCHAR(2048) NOT NULL,
	price DECIMAL(6,2) NOT NULL,
	link VARCHAR(50) NOT NULL,
	subCategoryID VARCHAR(25) NOT NULL,
	availability ENUM('T', 'F') NOT NULL,
	stockLevel SMALLINT(5) UNSIGNED NOT NULL
);
CREATE TABLE project_tbl (
	projectID SMALLINT(5) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	memberID SMALLINT(5) UNSIGNED,
	technicianID SMALLINT(5) UNSIGNED NOT NULL,
	cost DECIMAL(6,2),
	details TEXT,
	status ENUM('Archived', 'Complete', 'Incomplete (Diagnosing)', 'Incomplete (Acquiring Parts)', 'Incomplete (Repairing)') NOT NULL DEFAULT 'Incomplete (Diagnosing)',
	eDC DATE NOT NULL,
	dateStarted DATE NOT NULL,
	dateCompleted DATE,
	FOREIGN KEY (memberID) REFERENCES member_tbl(memberID),
	FOREIGN KEY (technicianID) REFERENCES staff_tbl(staffID)
);
CREATE TABLE reset_tbl (
	code VARCHAR(25) NOT NULL,
    memberID SMALLINT(5) UNSIGNED NOT NULL,
	FOREIGN KEY (memberID) REFERENCES member_tbl(memberID)
);
CREATE TABLE staff_tbl (
	staffID SMALLINT(5) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(4) NOT NULL,
	firstName VARCHAR(25) NOT NULL,
	lastName VARCHAR(25) NOT NULL,
	gender ENUM('F', 'M') NOT NULL,
	email VARCHAR(25) NOT NULL UNIQUE,
	password VARCHAR(32) NOT NULL,
	contactNo VARCHAR(8) NOT NULL,
	userType ENUM('1', '2') NOT NULL
);
CREATE TABLE sub_category_tbl (
	subCategoryID TINYINT(3) NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	subCategory VARCHAR(25) NOT NULL,
	description VARCHAR(255) NOT NULL
);
CREATE TABLE technician_tbl (
	technicianID SMALLINT(5) UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	totalProjects SMALLINT(5) NOT NULL,
	FOREIGN KEY (technicianID) REFERENCES staff_tbl(staffID)
);

-- Data Seeding
INSERT INTO cart_tbl (memberID) VALUES
(1),
(2);
INSERT INTO category_tbl (parentCategoryID, subCategoryID) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5);
INSERT INTO manager_tbl (managerID, promote) VALUES
(1, "F");
INSERT INTO member_tbl (title, firstName, lastName, gender, email, password, DOB, contactNo) VALUES
("Mr.", "Third", "Last", "M", "third@member.com", "1a1dc91c907325c69271ddf0c944bc72", "1996-01-01", "555-5553"),
("Mr.", "Fourth", "Last", "M", "fourth@member.com", "1a1dc91c907325c69271ddf0c944bc72", "1996-01-01", "555-5554");
INSERT INTO parent_category_tbl (parentCategory, description) VALUES
("Secondary", "Items which can be used to augment another."),
("Storage", "Items used to retain data."),
("Other", "Miscellaneous items.");
INSERT INTO product_cart_tbl (cartID, productID) VALUES
(1, 5),
(1, 10),
(1, 11);
INSERT INTO product_tbl (product, image, details, price, link, subCategoryID, availability, stockLevel) VALUES
("Linkin Park - Hybrid Theory", "image1.jpg", "Hybrid Theory contains many of Linkin Park's well known hits.", "39.50", "link", 5, "T", 100),
("Linkin Park - Meteora", "image2.jpg", "Meteora has some iconic tracks including Numb. It is packed with what has proven to be highly popular music known around the world.", "45.99", "link", 5, "T", 100),
("Linkin Park - Minutes to Midnight", "image3.jpg", "One of their more recent offerings, Minutes to Midnight offers more good music.", "129.99", "link", 5, "T", 100),
("Standard QWERTY Keyboard", "image4.jpg", "This is a standard QWERTY keyboard, in other words, it is the type of keyboard you usually see. It comes with some additional functionality via a array of keys at the top which can improve your productivity and efficiency at the computer desk.<br><br>", "89.99.", "link", 1, "T", 100),
("Standard Computer Housing", "image5.jpg", "This computer housing features a small utility section with USB and audio/video jacks which provide convenience for your device connection needs.<br><br>", "457.00", "link", 2, "T", 100),
("Standard Mouse", "image6.jpg", "This is the standard mouse with left-click, right-click and scroll options. The scroll wheel can be used as a third click option. This is an optical mouse which performs well with any mouse mat and it works with most modern computer systems.<br><br>", "457.00", "link", 2, "T", 100),
("2GB Sandisk Flash Drive", "image7.jpg", "This flash drive's connector can be flipped out to provide with a consistently flat flash drive.<br><br>", "110.99", "link", 4, "T", 100),
("1GB Diamond Guitar Flash Drive", "image8.jpg", "This very special flash drive comes with diamonds on its surface and is fashioned in the shape of a guitar.<br><br>", "844.99", "link", 4, "T", 100),
("1GB Copper Colour Gun Flash Drive", "image9.jpg", "This  special flash drive comes with flash drive in the shape of a handgun. Its connector comes in the form of the magazine which can be removed.<br><br>", "438.50", "link", 4, "T", 100),
("Titanium Laptop Briefcase", "image10.jpg", "This high-end luxury item will protect your laptop and the various accessories and items you can store within it.<br><br>", "1754.99", "link", 2, "T", 100),
("Standard Laptop Carrying Case", "image11.jpg", "This carrying case offers sufficient room for your laptop, charger and even a mouse along with a number of pockets and areas for you to store any additional items you want to carry.<br><br>", "516.99", "link", 2, "T", 100),
("3TB FD External Drive", "image12.jpg", "This excellent external drive provides you with both fast data storage and a network connection so you can share your files on your network. This external drive requires its own power source.<br><br>", "2128.00", "link", 3, "T", 100),
("1GB DT Micro Flash Drive", "image13.jpg", "This compact flash drive provides storage convenience in a small package of the width of a finger.<br><br>", "82.99", "link", 4, "T", 100),
("USB Adapter Cable", "image14.jpg", "This cable will allow you to connect a USB device with a smaller connection. It is a Hi-Speed USB Cable with a length of 6 ft. or 1.8 m.<br><br>", "24.99", "link", 1, "T", 100),
("iPhone 5 Case", "image15.jpg", "This case is built specifically for your iPhone and provides excellent protection from accidental drops and stains. It it light and offers plenty of room for your camera and the main screen.<br><br>", "110.50", "link", 2, "T", 100),
("2TB Pocket External Drive", "image16.jpg", "This drive provides you with a large amount of storage capacity for such a device of its size. It is fully USB powered.<br><br>", "328.00", "link", 3, "T", 100),
("Heavy Duty Computer Case", "image17.jpg", "Ideal for powerful computers and servers, this case will the cooling area you need to protect such a device by allowing sufficient room for fans to use to remove the heat.<br><br>", "412.99", "link", 2, "T", 100),
("2TB Pocket External Drive", "image18.jpg", "This drive provides you with a large amount of storage capacity for such a device of its size as well as sturdy protection from accidental falls. It is fully USB powered.<br><br>", "480.99", "link", 3, "T", 100),
("2TB WD My Passport External Drive", "image19.jpg", "This drive provides you with a large amount of storage capacity for such a device of its size. It is fully USB powered.<br><br>", "342.50", "link", 3, "T", 100),
("8MP Webcam", "image20.jpg", "This standard webcam, which can take 8MP quality images and 720p video, is ideal for both voice chats and video conferencing.<br><br>", "219.99", "link", 1, "T", 100),
("1GB Diamond Covered Flask Flash Drive", "image21.jpg", "This ver special flash drive comes in the form of a flask covered in diamonds. Its top can be removed to reveal the connector.<br><br>", "1249.99", "link", 4, "T", 100),
("1GB Silver/Diamond Heart Flash Drive", "image22.jpg", "This very special flash drive comes in the shape of a heart with one side covered in smooth silver and the other in diamonds.<br><br>", "200.00", "link", 4, "T", 100),
("2TB Hitachi External Drive", "image23.jpg", "This drive provides you with a large amount of storage capacity for such a device of its size. It is fully USB powered.<br><br>", "750.00", "link", 3, "T", 100),
("32GB Verbatim Flash Drive", "image24.jpg", "This simple, small flash drive has a high storage capacity and is also very thin which makes it convenient to carry.<br><br>", "85.00", "link", 4, "T", 100),
("2TB Maxtor External Drive", "image25.jpg", "This drive provides you with a large amount of storage capacity for such a device of its size. It is fully USB powered.<br><br>", "675.00", "link", 3, "T", 100);
INSERT INTO project_tbl (memberID, technicianID, details, status, cost, eDC, dateStarted, dateCompleted) VALUES
(1, 2, "Project number one.", "Incomplete (Repairing)", 200, "2014-01-20", "2014-01-18", ""),
(1, 2, "Project number two.", "Complete", 300, "2014-01-19", "2014-01-18", "2014-01-19"),
(1, 2, "Project number three.", "Archived", 400, "2014-01-19", "2014-01-18", "2014-01-19");
INSERT INTO staff_tbl (title, firstName, lastName, gender, email, password, contactNo, userType) VALUES
("Mr.", "First", "Last", "M", "first@last.com", "1a1dc91c907325c69271ddf0c944bc72", "555-5551", 1),
("Mr.", "Second", "Last", "M", "second@last.com", "1a1dc91c907325c69271ddf0c944bc72", "555-5552", 2);
INSERT INTO sub_category_tbl (subCategory, description) VALUES
("Accessories", "Items used to add functionality to an item."),
("Cases", "Items used to carry other items."),
("External Hard Drives", "Portable hard disk drives."),
("Flash Drives", "Portable flash memory drives."),
("Music", "Music albums.");
INSERT INTO technician_tbl (technicianID, totalProjects) VALUES
(2, 3);