
INSERT INTO `{PREF}_tracker_host_grp` (`NAME`, `KEY_VAR`, `BAN`, `REGULAR_EXPRESSION`, `REGULAR_EXPRESSION2`) VALUES 
('Google', 'q', '0', 'google\\.', ''),
('Altavista', 'q', '0', 'altavista\\.', ''),
('Yahoo', 'p', '0', 'yahoo\\.', 'mail'),
('MSN', 'q', '0', 'msn\\.', ''),
('AOL', 'query', '0', 'search\\.aol\\.co', ''),
('Alltheweb', 'q', '0', 'alltheweb\\.com', ''),
('Lycos', 'query', '0', 'lycos\\.', ''),
('MyWay', 'searchfor', '0', '', ''),
('Voila', 'kw', '0', 'voila\\.', ''),
('Tiscali', '', '0', 'tiscali\\.', ''),
('Alexa', 'q', '0', 'alexa\\.com', ''),
('A9', '', '0', 'a9\\.com', ''),
('Dmoz', 'search', '0', 'dmoz\\.org', ''),
('Netscape', 'search', '0', 'netscape\\.', ''),
('Terra', 'query', '0', 'search\\.terra\\.', ''),
('Search.com', 'q', '0', 'www\\.search\\.com', ''),
('Yandex', 'text', '0', 'yandex\\.ru|ya\\.ru', ''),
('Aport', 'r', '0', 'aport\\.ru', ''),
('Rambler', 'words', '0', 'rambler\\.ru', ''),
('Ask Jeeves', 'q', '0', '\\.ask\\.com', ''),
('Excite', 'search', '0', 'excite\\.', '');



INSERT INTO `{PREF}_tracker_visitor_agent_grp` (`ID`, `NAME`, `BAN`, `REGULAR_EXPRESSION`, `REGULAR_EXPRESSION2`) VALUES 


(1, 'IE 4', '0', 'MSIE 4\\.[0-9]{1,3}', 'Opera'),
(2, 'IE 5', '0', 'MSIE 5\\.[0-9]{1,3}', 'Opera'),
(3, 'IE 6', '0', 'MSIE 6\\.[0-9]{1,3}', 'Opera'),
(4, 'IE 7', '0', 'MSIE 7\\.[0-9]{1,3}', 'Opera'),

(5, 'Firefox', '0', 'Firefox', ''),

(6, 'Opera < 5', '0', 'Opera.[1-4]', ''),
(7, 'Opera 5', '0', 'Opera.5\\.[0-9]{1,3}', ''),
(8, 'Opera 6', '0', 'Opera.6\\.[0-9]{1,3}', ''),
(9, 'Opera 7', '0', 'Opera.7\\.[0-9]{1,3}', ''),
(10, 'Opera 8', '0', 'Opera.8\\.[0-9]{1,3}', ''),


(11, 'Safari', '0', 'Safari\\/[0-9]+', ''),

(12, 'Netscape 7', '0', 'Netscape.7\\.[0-9]{1,3}', ''),
(13, 'Konqueror 3', '0', 'Konqueror 3', ''),
(14, 'Mozilla', '0', 'Gecko', 'Firefox|Netscape|Safari'),
(15, 'Robots', '1', '', '');