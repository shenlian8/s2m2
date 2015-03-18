# Welcome to the s2m2 wiki!

**This is a PHP-program to show, how to convert Gregorian Calendar to Chinese Calendar.
The range of the input is from 1901-01-01 to 2100-12-31**

![s2m2](https://cloud.githubusercontent.com/assets/9590431/6704681/4b013362-cd4c-11e4-9139-d6f97e4e91a5.jpg)

[Demo](http://shenlian.de/s2m2.php)

It returns an array:

```php
Array ( [year] => 数字农历年
        [cyear] => 中文农历年 
        [yearten] => 数字天干
        [cyearten] => 中文天干
        [yeartwelve] => 数字地支
        [cyeartwelve] => 中文地支
        [month] => 数字农历月，闰月用1xx的形式表示比如闰5月为105
        [cmonth] => 中文农历月
        [day] => 数字农历日
        [cday] => 中文农历日
        [weekday] => 数字星期日期，〇为星期天
        [cweekday] => 中文星期日期 ) 
```

To use the the class:
```php
$a = new s2m();
$re = $a->getMDate(2001, 3, 12);
print_r($re);
```
