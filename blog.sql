-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-03-19 12:42:07
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `parent`, `name`) VALUES
(1, 0, '未分类'),
(37, 0, '梦里花落&Hope'),
(38, 37, '摘录'),
(40, 0, 'COMRADE'),
(42, 37, '2333'),
(43, 42, '555');

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE `comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `isuser` tinyint(1) NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `name` tinytext,
  `url` tinytext,
  `about` text,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `passage`
--

CREATE TABLE `passage` (
  `id` int(10) UNSIGNED NOT NULL,
  `rid` int(10) UNSIGNED NOT NULL COMMENT 'release id',
  `uid` int(10) UNSIGNED NOT NULL,
  `title` tinytext NOT NULL,
  `content` longtext NOT NULL,
  `category` int(10) UNSIGNED DEFAULT '1',
  `releasetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `passage`
--

INSERT INTO `passage` (`id`, `rid`, `uid`, `title`, `content`, `category`, `releasetime`) VALUES
(3, 3, 9, '123', '123', 1, '2017-01-19 04:37:35'),
(4, 4, 9, '27237', '7867', 1, '2017-01-19 04:37:35'),
(5, 5, 9, '你好', '你好吗ddd', 1, '2017-01-19 04:37:35'),
(6, 6, 9, '<span style=\'color:red;\'>红色标题</span>', '<span style=\'color:red;\'>红色文章</span>', 1, '2017-01-19 04:37:35'),
(7, 7, 9, '', '', 1, '2017-01-19 04:37:35'),
(8, 7, 9, 'kgjg查收', '擦阿法法法发生违法', 1, '2017-01-19 04:37:35'),
(9, 6, 9, '<span style="color:red;">红色标题</span>', '<span style=\'color:red;\'>红色文章</span>', 1, '2017-01-19 04:37:35'),
(10, 6, 9, '<span style="color:red;">红色标题</span>', '<span style=\'color:red;\'>红色文章</span>\'"\'"', 1, '2017-01-19 04:37:35'),
(11, 6, 9, '<span style="color:red;">红色标题</span>', '<span style=\'color:red;\'>红色文章</span>\'"\'"\r\n', 1, '2017-01-19 04:37:35'),
(12, 6, 9, '<span style="color:red;">红色标题</span>', '<span style=\'color:red;\'>红色文章</span>\r\n', 1, '2017-01-19 04:37:35'),
(15, 6, 16, '<span style=\'color:red;\'>红色标题</span>', '<span style=\'color:red;\'>红色文章</span>', 1, '2017-01-19 04:37:35'),
(16, 6, 16, '<span style=\'color:red;\'>红色标题2</span>', '<span style=\'color:red;\'>红色文章2</span>', 1, '2017-01-19 04:37:35'),
(17, 11, 16, '2017 寒假待办', '<strong style="font-size:1.5em">1. 校学生会任务：个人博客的网页前后端</strong>\r\n\r\n<strong style="font-size:1.5em">2. 微博协会副本任务：游戏后台分工合作</strong>\r\n\r\n<strong style="font-size:1.5em">3. 个人任务：完善并整合网站的用户系统</strong>', 1, '2017-01-19 04:37:35'),
(18, 11, 16, '2017 寒假待办', '<p><strong style="font-size:1.5em">1. 校学生会任务：个人博客的网页前后端</strong></p>\r\n<p><strong style="font-size:1.5em">2. 微博协会副本任务：游戏后台分工合作</strong></p>\r\n<p><strong style="font-size:1.5em">3. 个人任务：完善并整合网站的用户系统</strong></p>', 1, '2017-01-19 04:37:35'),
(19, 7, 16, '长标题2333333333333333333333333333333333333333333333333333333333', '擦阿法法法发生违法', 1, '2017-01-19 04:37:35'),
(20, 7, 16, '长标题233333333333长标题233333333333长标题233333333333长标题233333333333', '擦阿法法法发生违法', 1, '2017-01-19 04:37:35'),
(21, 7, 16, '长标题233333333333长标题233333333333长标题233333333333长标题23333333333', '擦阿法法法发生违法', 1, '2017-01-19 04:37:35'),
(22, 7, 16, '长标题233333333333长标题233333333333', '擦阿法法法发生违法', 1, '2017-01-19 04:37:35'),
(23, 12, 16, '文章2333', '内容2333', 1, '2017-01-19 04:37:35'),
(24, 7, 16, '长标题233333333333长标题2333333333333333333', '擦阿法法法发生违法', 1, '2017-01-19 04:37:35'),
(25, 12, 16, '文章2333', '内容2333', 1, '2017-01-26 13:28:56'),
(26, 6, 16, '<span style=\'color:red;\'>红色标题2</span>', '<span style=\'color:red;\'>红色文章2</span>', 1, '2017-01-26 01:45:59'),
(27, 12, 16, '文章2333', '内容2333', 1, '2017-01-26 01:45:59'),
(28, 11, 16, '2017 寒假待办', '<p><strong style="font-size:1.5em">1. 校学生会任务：个人博客的网页前后端</strong></p>\r\n<p><strong style="font-size:1.5em">2. 微博协会副本任务：游戏后台分工合作</strong></p>\r\n<p><strong style="font-size:1.5em">3. 个人任务：完善并整合网站的用户系统</strong></p>', 1, '2017-01-26 13:28:56'),
(29, 13, 16, '2333', '233333333333333', 1, '2017-01-26 13:05:45'),
(30, 12, 16, '文章2333', '内容2333', 1, '2017-01-26 01:45:59'),
(31, 12, 16, '文章2333', '内容2333', 1, '2017-01-26 13:28:56'),
(32, 6, 16, '<span style=\'color:red;\'>红色标题2</span>', '<span style=\'color:red;\'>红色文章2</span>', 1, '2017-01-26 13:28:56'),
(33, 13, 16, '2333', '233333333333333', 1, '2017-01-26 13:06:16'),
(34, 13, 16, '2333', '233333333333333', 1, '2017-01-26 14:44:25'),
(35, 13, 2, '2333', '233333333333333', 37, '2017-03-17 11:28:06');

-- --------------------------------------------------------

--
-- 表的结构 `passage_release`
--

CREATE TABLE `passage_release` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `version` int(10) UNSIGNED DEFAULT NULL COMMENT '=pid'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `passage_release`
--

INSERT INTO `passage_release` (`id`, `uid`, `version`) VALUES
(3, 9, 3),
(4, 9, 4),
(5, 9, 5),
(6, 9, 32),
(7, 9, 24),
(12, 16, 31),
(11, 16, 28),
(13, 16, 35);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `uid` int(10) UNSIGNED NOT NULL,
  `level` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0=read_only;1=write_need_pass;3=read_write;7=read_write_pass;15=read_write_pass_media;127=admin;255=root',
  `activetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`uid`, `level`, `activetime`) VALUES
(2, 127, '2017-01-04 15:41:27'),
(9, 255, '2017-01-04 15:48:29'),
(16, 127, '2017-01-07 05:07:48'),
(8, 0, '2017-01-21 05:59:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `time` (`time`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `passage`
--
ALTER TABLE `passage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `releasetime` (`releasetime`),
  ADD KEY `pid` (`rid`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `passage_release`
--
ALTER TABLE `passage_release`
  ADD PRIMARY KEY (`id`),
  ADD KEY `version` (`version`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `activetime` (`activetime`),
  ADD KEY `level` (`level`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- 使用表AUTO_INCREMENT `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `passage`
--
ALTER TABLE `passage`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- 使用表AUTO_INCREMENT `passage_release`
--
ALTER TABLE `passage_release`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
