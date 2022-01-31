CREATE TABLE users(
    userID INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    privateKey VARCHAR(4096) NOT NULL,
    publicKey VARCHAR(4096) NOT NULL,
    PRIMARY KEY (userID)
);

CREATE TABLE contacts(
    userIDlow INT NOT NULL,
    userIDhigh INT NOT NULL,
    PRIMARY KEY (userIDlow, userIDhigh)

);

CREATE TABLE messages(
    messageID INT NOT NULL AUTO_INCREMENT,
    userIDlow INT NOT NULL,
    userIDhigh INT NOT NULL,
    senderID INT NOT NULL,
    message TEXT(65525) NOT NULL, 
    PRIMARY KEY (messageID),
    FOREIGN KEY (userIDlow, userIDhigh) REFERENCES contacts(userIDlow, userIDhigh)
);

CREATE TABLE settings(
    userID INT NOT NULL,
    font VARCHAR(255),
    backgroundCol VARCHAR(255),
    textCol VARCHAR(255),
    hoverCol VARCHAR(255),
    secondaryCol VARCHAR(255),
    PRIMARY KEY (userID) REFERENCES users(userID)
);