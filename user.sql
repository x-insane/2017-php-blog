-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-03-19 13:17:46
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `user`
--

-- --------------------------------------------------------

--
-- 表的结构 `appinfo`
--

CREATE TABLE `appinfo` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `addr` tinytext NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `appinfo`
--

INSERT INTO `appinfo` (`id`, `name`, `addr`, `create_time`) VALUES
(1, '留言板', 'message', '2016-09-30 16:00:00'),
(2, '机器人', 'robot', '2016-10-31 18:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `applist`
--

CREATE TABLE `applist` (
  `id` int(10) UNSIGNED NOT NULL,
  `aid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `invite_code`
--

CREATE TABLE `invite_code` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `code` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `pwd` tinytext NOT NULL,
  `pwdwrongtimes` int(11) DEFAULT '0',
  `level` tinytext NOT NULL,
  `sex` tinytext NOT NULL,
  `mailbox` tinytext NOT NULL,
  `mailbox_bindcode` tinytext,
  `mailbox_verifycode` tinytext,
  `tel` tinytext NOT NULL,
  `qq` tinytext NOT NULL,
  `ques` tinytext NOT NULL,
  `ans` tinytext NOT NULL,
  `applist` text NOT NULL,
  `true_name` tinytext NOT NULL,
  `invite_code` tinytext NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogintime` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `pwd`, `pwdwrongtimes`, `level`, `sex`, `mailbox`, `mailbox_bindcode`, `mailbox_verifycode`, `tel`, `qq`, `ques`, `ans`, `applist`, `true_name`, `invite_code`, `regtime`, `lastlogintime`) VALUES
(0, 'root', 'root', 0, 'root', '', '', NULL, NULL, '', '', '', '', '[]', '', '', '2016-10-24 04:17:26', '2017-01-07 08:16:40'),

-- --------------------------------------------------------

--
-- 表的结构 `verifylist`
--

CREATE TABLE `verifylist` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `todo` tinytext,
  `code` tinytext,
  `content` tinytext,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `canfreshtime` timestamp NOT NULL,
  `deadline` timestamp NULL DEFAULT NULL,
  `pass` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `verifylist`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appinfo`
--
ALTER TABLE `appinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `create_time` (`create_time`);

--
-- Indexes for table `applist`
--
ALTER TABLE `applist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aid` (`aid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `addtime` (`addtime`);

--
-- Indexes for table `invite_code`
--
ALTER TABLE `invite_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regtime` (`regtime`),
  ADD KEY `lastlogintime` (`lastlogintime`);

--
-- Indexes for table `verifylist`
--
ALTER TABLE `verifylist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `addtime` (`addtime`),
  ADD KEY `deadline` (`deadline`),
  ADD KEY `canfreshtime` (`canfreshtime`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `appinfo`
--
ALTER TABLE `appinfo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `applist`
--
ALTER TABLE `applist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `invite_code`
--
ALTER TABLE `invite_code`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `verifylist`
--
ALTER TABLE `verifylist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;