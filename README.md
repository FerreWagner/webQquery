QueryList && ThinkPHP5 Crawl
===============

委托爬虫,集成ThinkPHP5和QueryList4

----------


- Tips1:QueryList环境为PHP7，而PHPExcel环境为PHP5，因此会存在兼容问题，Excel导出时出现问题，无法导出EXCEL原因为Shared/OLE.php第290行使用continue，PHP7不支持，修改为continue 2即可。来自：[https://www.cnblogs.com/lovebing/p/11199294.html](https://www.cnblogs.com/lovebing/p/11199294.html)

