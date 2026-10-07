-- Схема базы данных Hercules (без личных данных).
-- Импорт: mysql -u root < schema.sql   (или вкладка «Импорт» в phpMyAdmin)
-- Колонки из migrations/001 уже включены: миграция нужна только для старых баз.

CREATE DATABASE IF NOT EXISTS `hercules`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `hercules`;

CREATE TABLE `lessons` (
  `LessonID`   int(11) NOT NULL AUTO_INCREMENT,
  `LessonName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`LessonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `students` (
  `StudentID`    int(11) NOT NULL AUTO_INCREMENT,
  `FirstName`    text NOT NULL,
  `Phone`        varchar(20) NOT NULL,
  `Email`        varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `PR`           varchar(50) NOT NULL,
  PRIMARY KEY (`StudentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `teachers` (
  `TeacherID`    int(11) NOT NULL AUTO_INCREMENT,
  `FirstName`    varchar(100) NOT NULL,
  `LastName`     varchar(100) NOT NULL,
  `Phone_Number` varchar(20) DEFAULT NULL,
  `Email`        varchar(255) DEFAULT NULL,
  `PasswordHash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`TeacherID`),
  UNIQUE KEY `uniq_teachers_email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `schedule` (
  `ScheduleID`  int(11) NOT NULL AUTO_INCREMENT,
  `StudentID`   int(11) NOT NULL,
  `TeacherID`   int(11) NOT NULL,
  `LessonID`    int(11) DEFAULT NULL,
  `SessionDate` date DEFAULT NULL,
  `SessionTime` time DEFAULT NULL,
  `Location`    varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ScheduleID`),
  KEY `StudentID` (`StudentID`),
  KEY `TeacherID` (`TeacherID`),
  KEY `LessonID` (`LessonID`),
  CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`),
  CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`TeacherID`) REFERENCES `teachers` (`TeacherID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL AUTO_INCREMENT,
  `StudentID`    int(11) DEFAULT NULL,
  `LessonID`     int(11) DEFAULT NULL,
  `Present`      tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`AttendanceID`),
  KEY `StudentID` (`StudentID`),
  KEY `LessonID` (`LessonID`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`),
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`LessonID`) REFERENCES `lessons` (`LessonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Справочник уроков (не личные данные)
INSERT INTO `lessons` (`LessonName`) VALUES
  ('Full Body Blast'),
  ('Leg Day Annihilation'),
  ('Upper Body Push'),
  ('Upper Body Pull'),
  ('Powerlifting Technique'),
  ('Yoga & Mobility Flow'),
  ('High-Intensity Cardio'),
  ('Core & Abs Sculpt'),
  ('Olympic Weightlifting'),
  ('Open Gym Time');
