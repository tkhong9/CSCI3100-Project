-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2022-04-02 11:45:02
-- 伺服器版本： 10.4.22-MariaDB
-- PHP 版本： 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `unisched`
--

-- --------------------------------------------------------

--
-- 資料表結構 `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `courseCode` varchar(255) NOT NULL,
  `courseTitle` varchar(255) NOT NULL,
  `unit` int(11) NOT NULL,
  `startTime` varchar(255) NOT NULL,
  `endTime` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `courses`
--

INSERT INTO `courses` (`course_id`, `courseCode`, `courseTitle`, `unit`, `startTime`, `endTime`, `day`, `location`) VALUES
(1, 'CSCI3100', 'Software Engineering', 3, '10:00', '12:00', 'Monday', 'Chung Chi College Chapel'),
(2, 'CSCI3150', 'Introduction to Operating Systems', 3, '14:00', '16:00', 'Wednesday', 'Lee Shau Kee Building LT1'),
(3, 'IERG4210', 'Web Programming and Security', 3, '10:00', '12:00', 'Friday', 'Lady Shaw Bldg LT6'),
(4, 'CSCI2040', 'Introduction to Python', 2, '8:00', '10:00', 'Tuesday', 'Basic Med Sci Bldg G18'),
(5, 'ENGG1110', 'Problem Solving By Programming', 3, '10:00', '12:00', 'Friday', 'Mong Man Wai Bldg LT1');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
