
CREATE TABLE IF NOT EXISTS `Qurbanis` (
  `qurbaniKey` INT(11) NOT NULL AUTO_INCREMENT,
  `qurbaniyear` VARCHAR(4) NOT NULL,
  `sheep` INT(11) NOT NULL,
  `cows` INT(11) NOT NULL,
  `camels` INT(11) NOT NULL,
  `total` INT(11) NOT NULL,
  `fullname` VARCHAR(100) NULL DEFAULT NULL,
  `email` VARCHAR(100) NULL DEFAULT NULL,
  `mobile` VARCHAR(100) NULL DEFAULT NULL,
  `instructions` TEXT NULL DEFAULT NULL,
  `donationid` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`qurbaniKey`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;
