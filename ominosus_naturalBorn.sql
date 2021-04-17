-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2021 at 11:30 PM
-- Server version: 5.7.33
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ominosus_naturalBorn`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `image` text NOT NULL,
  `otp` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `designation`, `education`, `phone`, `address`, `image`, `otp`) VALUES
(7, 'CINEMAFLIX', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Software Engineer', 'Pune', '9996012990', 'Ambala', 'uploads/admin/1607858620_CINEMAFLIX app icon 512 by 512 logo.JPG', 5387),
(6, 'CINEMAFLIX', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Software Engineer', 'Pune', '9877245562', 'Ambala', 'uploads/admin/1607858620_CINEMAFLIX app icon 512 by 512 logo.JPG', 2075),
(4, 'admin', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Software Engineer', 'Pune', '7087772970', 'Ambala', 'uploads/admin/1615558158_Admin (1).png', 6725);

-- --------------------------------------------------------

--
-- Table structure for table `adminVideoHitCount`
--

CREATE TABLE `adminVideoHitCount` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `adminVideoLimit`
--

CREATE TABLE `adminVideoLimit` (
  `id` int(11) NOT NULL,
  `setLimit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `adminVideos`
--

CREATE TABLE `adminVideos` (
  `id` int(11) NOT NULL,
  `videoTitle` text NOT NULL,
  `videoUrl` text NOT NULL,
  `videoPath` text NOT NULL,
  `buttonName` text NOT NULL,
  `sponsored` text NOT NULL,
  `setLimit` int(11) NOT NULL,
  `status` text NOT NULL,
  `viewCount` int(11) NOT NULL,
  `hitCount` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adminVideos`
--

INSERT INTO `adminVideos` (`id`, `videoTitle`, `videoUrl`, `videoPath`, `buttonName`, `sponsored`, `setLimit`, `status`, `viewCount`, `hitCount`, `created`, `updated`) VALUES
(1, 'Website Development', 'https://omninos.in/', 'http://d29vf2dbcha7zq.cloudfront.net/brideoGoListsnew.mp4', 'check now', 'omninos', 0, 'Pending', 0, 0, '2021-03-30 09:31:50', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `adminVideoView`
--

CREATE TABLE `adminVideoView` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `userViewType` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(15) NOT NULL,
  `likes` text NOT NULL,
  `title` text NOT NULL,
  `followers` text NOT NULL,
  `color` text NOT NULL,
  `image` text NOT NULL,
  `totalFollowers` text NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blockUser`
--

CREATE TABLE `blockUser` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `blockUserId` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `image` text NOT NULL,
  `series` text NOT NULL,
  `backImage` text NOT NULL,
  `videoCount` text NOT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coin`
--

CREATE TABLE `coin` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `title` text NOT NULL,
  `coin` text NOT NULL,
  `price` text NOT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reciver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `deleteChat` text NOT NULL,
  `messageType` text NOT NULL,
  `image` text NOT NULL,
  `readStatus` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deleteAccountRequest`
--

CREATE TABLE `deleteAccountRequest` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `favouriteHashTagList`
--

CREATE TABLE `favouriteHashTagList` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `hashtagId` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `favouriteSoundList`
--

CREATE TABLE `favouriteSoundList` (
  `id` int(15) NOT NULL,
  `userId` int(12) NOT NULL,
  `soundId` int(12) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0 no,1 yes',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Gems`
--

CREATE TABLE `Gems` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `count` text NOT NULL,
  `price` text NOT NULL,
  `status` text NOT NULL,
  `image` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gift`
--

CREATE TABLE `gift` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `primeAccount` text NOT NULL,
  `nonPrimeAccount` text NOT NULL,
  `image` text NOT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag`
--

CREATE TABLE `hashtag` (
  `id` int(11) NOT NULL,
  `hashtag` text NOT NULL,
  `userId` int(11) NOT NULL,
  `videoCount` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reciver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `deleteChat` text NOT NULL,
  `messageType` text NOT NULL,
  `image` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(15) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `liveBroadcast`
--

CREATE TABLE `liveBroadcast` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `broadcast_id` text NOT NULL,
  `author` text NOT NULL,
  `length` text NOT NULL,
  `preview` text NOT NULL,
  `resourceUri` text NOT NULL,
  `title` text NOT NULL,
  `type` text NOT NULL,
  `height` text NOT NULL,
  `width` text NOT NULL,
  `ingestChannel` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `clientVersion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logo`
--

CREATE TABLE `logo` (
  `id` int(11) NOT NULL,
  `img` text NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE `moderators` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `password` text NOT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `muteUserNotification`
--

CREATE TABLE `muteUserNotification` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `muteId` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(12) NOT NULL,
  `name` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `status` enum('1','2') NOT NULL COMMENT '1 active,2 inactive',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `problemReport`
--

CREATE TABLE `problemReport` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `created` datetime(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `problemReportUser`
--

CREATE TABLE `problemReportUser` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `report` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pushMessage`
--

CREATE TABLE `pushMessage` (
  `id` int(11) NOT NULL,
  `user_type` text NOT NULL,
  `user_name` text NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reportUser`
--

CREATE TABLE `reportUser` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `reportUserId` int(11) NOT NULL,
  `report` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `socialLinks`
--

CREATE TABLE `socialLinks` (
  `id` int(11) NOT NULL,
  `facebook` text NOT NULL,
  `twitter` text NOT NULL,
  `instagram` text NOT NULL,
  `skype` text NOT NULL,
  `googlePlus` text NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sounds`
--

CREATE TABLE `sounds` (
  `id` int(11) NOT NULL,
  `title` varchar(55) NOT NULL,
  `userId` int(11) NOT NULL,
  `sound` text NOT NULL,
  `soundImg` varchar(255) NOT NULL,
  `type` text NOT NULL,
  `soundCount` int(11) NOT NULL,
  `addedby` enum('0','1') NOT NULL COMMENT '0 user, 1 admin',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subAdmin`
--

CREATE TABLE `subAdmin` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `password` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testing`
--

CREATE TABLE `testing` (
  `id` int(11) NOT NULL,
  `title` text,
  `sdfsdf` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userCoinHistory`
--

CREATE TABLE `userCoinHistory` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `coin` text NOT NULL,
  `transactionId` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userFollow`
--

CREATE TABLE `userFollow` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `followingUserId` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userGiftHistory`
--

CREATE TABLE `userGiftHistory` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `giftUserId` int(11) NOT NULL,
  `giftId` int(11) NOT NULL,
  `coin` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userNotification`
--

CREATE TABLE `userNotification` (
  `id` int(11) NOT NULL,
  `loginId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `adminId` int(15) NOT NULL,
  `videoId` int(11) NOT NULL,
  `commentId` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL,
  `type` text NOT NULL,
  `videoUrl` text NOT NULL,
  `notiDate` date NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userPanAndAadharCard`
--

CREATE TABLE `userPanAndAadharCard` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` text NOT NULL,
  `panAadharNumber` text NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userProfileInformation`
--

CREATE TABLE `userProfileInformation` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `videoCount` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userReportVideo`
--

CREATE TABLE `userReportVideo` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `report` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `badge` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loginOtp` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `privateAccount` int(11) NOT NULL,
  `likeVideo` int(11) NOT NULL,
  `followingUser` int(11) NOT NULL,
  `profilePhotoStatus` int(11) NOT NULL,
  `onlineStatus` int(11) NOT NULL,
  `coin` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchasedCoin` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `countryCode` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_id` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `video` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `followerCount` int(11) NOT NULL,
  `likeNotifaction` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 yes, 2 no',
  `commentNotification` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 yes, 2 no',
  `followersNotification` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 yes, 2 no',
  `messageNotification` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 yes, 2 no',
  `videoNotification` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 yes, 2 no',
  `reg_id` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_type` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `login_type` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userVideos`
--

CREATE TABLE `userVideos` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `hashTag` text NOT NULL,
  `downloadCount` int(11) NOT NULL,
  `description` varchar(6000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videoPath` text NOT NULL,
  `imageThumb` text NOT NULL,
  `allowComment` int(11) NOT NULL,
  `allowDuetReact` int(11) NOT NULL,
  `allowDownloads` int(11) NOT NULL,
  `viewVideo` int(11) NOT NULL,
  `soundId` text NOT NULL,
  `commentCount` int(11) NOT NULL,
  `viewCount` int(11) NOT NULL,
  `likeCount` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL COMMENT '0accept, 1 trending, 2 reject, 3 for non viewed videos',
  `adminComment` text NOT NULL,
  `videolength` text NOT NULL,
  `videoType` text NOT NULL,
  `created` datetime NOT NULL,
  `downloadPath` varchar(255) DEFAULT NULL,
  `rejectVideoTime` datetime DEFAULT NULL,
  `trendingTime` datetime DEFAULT NULL,
  `viewedTime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `videoComments`
--

CREATE TABLE `videoComments` (
  `id` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videoCommentsLikeOrUnlike`
--

CREATE TABLE `videoCommentsLikeOrUnlike` (
  `id` int(11) NOT NULL,
  `commentId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `videoHashtagList`
--

CREATE TABLE `videoHashtagList` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `hashTag` text NOT NULL,
  `videoId` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `videoLikeOrUnlike`
--

CREATE TABLE `videoLikeOrUnlike` (
  `id` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `videoReport`
--

CREATE TABLE `videoReport` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `videoSubComment`
--

CREATE TABLE `videoSubComment` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `commentId` int(11) NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videoTesting`
--

CREATE TABLE `videoTesting` (
  `id` int(11) NOT NULL,
  `video` text NOT NULL,
  `videoId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `viewVideo`
--

CREATE TABLE `viewVideo` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `userViewType` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `websiteImages`
--

CREATE TABLE `websiteImages` (
  `id` int(15) NOT NULL,
  `image` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image1` text NOT NULL,
  `image2` text NOT NULL,
  `image3` text NOT NULL,
  `playStore` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `videoUrl1` text NOT NULL,
  `videoUrl2` text NOT NULL,
  `videoUrl3` text NOT NULL,
  `videoUrl4` text NOT NULL,
  `videoUrl5` text NOT NULL,
  `videoUrl6` text NOT NULL,
  `videoUrl7` text NOT NULL,
  `videoUrl8` text NOT NULL,
  `videoUrl9` text NOT NULL,
  `footerContent1` text NOT NULL,
  `footerContent2` text NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adminVideoHitCount`
--
ALTER TABLE `adminVideoHitCount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adminVideoLimit`
--
ALTER TABLE `adminVideoLimit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adminVideos`
--
ALTER TABLE `adminVideos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adminVideoView`
--
ALTER TABLE `adminVideoView`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blockUser`
--
ALTER TABLE `blockUser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coin`
--
ALTER TABLE `coin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deleteAccountRequest`
--
ALTER TABLE `deleteAccountRequest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favouriteHashTagList`
--
ALTER TABLE `favouriteHashTagList`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favouriteSoundList`
--
ALTER TABLE `favouriteSoundList`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Gems`
--
ALTER TABLE `Gems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift`
--
ALTER TABLE `gift`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hashtag`
--
ALTER TABLE `hashtag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liveBroadcast`
--
ALTER TABLE `liveBroadcast`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logo`
--
ALTER TABLE `logo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moderators`
--
ALTER TABLE `moderators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `muteUserNotification`
--
ALTER TABLE `muteUserNotification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `problemReport`
--
ALTER TABLE `problemReport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `problemReportUser`
--
ALTER TABLE `problemReportUser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pushMessage`
--
ALTER TABLE `pushMessage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reportUser`
--
ALTER TABLE `reportUser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `socialLinks`
--
ALTER TABLE `socialLinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sounds`
--
ALTER TABLE `sounds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subAdmin`
--
ALTER TABLE `subAdmin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testing`
--
ALTER TABLE `testing`
  ADD KEY `id` (`id`);

--
-- Indexes for table `userCoinHistory`
--
ALTER TABLE `userCoinHistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userFollow`
--
ALTER TABLE `userFollow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userGiftHistory`
--
ALTER TABLE `userGiftHistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userNotification`
--
ALTER TABLE `userNotification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userPanAndAadharCard`
--
ALTER TABLE `userPanAndAadharCard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userProfileInformation`
--
ALTER TABLE `userProfileInformation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userReportVideo`
--
ALTER TABLE `userReportVideo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userVideos`
--
ALTER TABLE `userVideos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `viewedTime` (`id`);

--
-- Indexes for table `videoComments`
--
ALTER TABLE `videoComments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videoCommentsLikeOrUnlike`
--
ALTER TABLE `videoCommentsLikeOrUnlike`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videoHashtagList`
--
ALTER TABLE `videoHashtagList`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videoLikeOrUnlike`
--
ALTER TABLE `videoLikeOrUnlike`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videoReport`
--
ALTER TABLE `videoReport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videoSubComment`
--
ALTER TABLE `videoSubComment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videoTesting`
--
ALTER TABLE `videoTesting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `viewVideo`
--
ALTER TABLE `viewVideo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `websiteImages`
--
ALTER TABLE `websiteImages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `adminVideoHitCount`
--
ALTER TABLE `adminVideoHitCount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adminVideoLimit`
--
ALTER TABLE `adminVideoLimit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adminVideos`
--
ALTER TABLE `adminVideos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `adminVideoView`
--
ALTER TABLE `adminVideoView`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blockUser`
--
ALTER TABLE `blockUser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coin`
--
ALTER TABLE `coin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deleteAccountRequest`
--
ALTER TABLE `deleteAccountRequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favouriteHashTagList`
--
ALTER TABLE `favouriteHashTagList`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favouriteSoundList`
--
ALTER TABLE `favouriteSoundList`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Gems`
--
ALTER TABLE `Gems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gift`
--
ALTER TABLE `gift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hashtag`
--
ALTER TABLE `hashtag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox`
--
ALTER TABLE `inbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liveBroadcast`
--
ALTER TABLE `liveBroadcast`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logo`
--
ALTER TABLE `logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moderators`
--
ALTER TABLE `moderators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `muteUserNotification`
--
ALTER TABLE `muteUserNotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `problemReport`
--
ALTER TABLE `problemReport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `problemReportUser`
--
ALTER TABLE `problemReportUser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pushMessage`
--
ALTER TABLE `pushMessage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reportUser`
--
ALTER TABLE `reportUser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `socialLinks`
--
ALTER TABLE `socialLinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sounds`
--
ALTER TABLE `sounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subAdmin`
--
ALTER TABLE `subAdmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testing`
--
ALTER TABLE `testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userCoinHistory`
--
ALTER TABLE `userCoinHistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userFollow`
--
ALTER TABLE `userFollow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userGiftHistory`
--
ALTER TABLE `userGiftHistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userNotification`
--
ALTER TABLE `userNotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userPanAndAadharCard`
--
ALTER TABLE `userPanAndAadharCard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userProfileInformation`
--
ALTER TABLE `userProfileInformation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userReportVideo`
--
ALTER TABLE `userReportVideo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userVideos`
--
ALTER TABLE `userVideos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videoComments`
--
ALTER TABLE `videoComments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videoCommentsLikeOrUnlike`
--
ALTER TABLE `videoCommentsLikeOrUnlike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videoHashtagList`
--
ALTER TABLE `videoHashtagList`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videoLikeOrUnlike`
--
ALTER TABLE `videoLikeOrUnlike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videoReport`
--
ALTER TABLE `videoReport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videoSubComment`
--
ALTER TABLE `videoSubComment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videoTesting`
--
ALTER TABLE `videoTesting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `viewVideo`
--
ALTER TABLE `viewVideo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `websiteImages`
--
ALTER TABLE `websiteImages`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
