<?php
##################################################
#世纪万年历 第二版
#
#作者: 沈潋(S&S Lab)
#E-mail:shenlian@hotmail.com
#web: http://www.shenlian.de/freeware
#
#版权声明:
#作者开放版权
#允许无条件地使用本文件的全部或部分
#可作任何修改或增补
#如果您使用了本文件，请给作者写一封E-mail
#如有问题欢迎同作者联系
#谢谢您的关注!

#第二版说明
#时不我待，转眼已经8年了。
#8年中，有不相识的朋友为我指正错误，同我交流心得，
#点点滴滴实在是很快乐的经验。再次表示感谢！
#最近无意中看PHP 5的文档，看到不少新的日期函数，
#发现自己的知识落伍很久了。
#本来想利用这些函数，写一个简单一些的万年历，
#怎奈这些函数在时间上有所限制，并不很适合万年历。
#所以不得不继续使用第一版中很笨的办法。
#这一版我尽量多写注释，以期更多人能读懂。
#另外，这一版改成面向对象的编程方式，
#可以查询的日期从公历1901年1月1日到2100年12月31日。
#我想我是活不到这个程序过期的那一天了。
#祝愿我们都快乐地度过人生中的每一天！
##################################################
##############################
#主程序部分
class S2M
{
    #常量，定义最小年份和最大年份
    public $MinYear = 1901;
    public $MaxYear = 2100;
    
    #农历每月的天数
    #很多朋友问这个数组的意义，我大致解释一下。数组的第一维，表示农历一年。
    #第二维，元素0（即下标为0的那个元素），表示农历这一年，哪一个月是闰月。
    #比如这一年闰五月。第一个数字就是5。如果这一年没有闰月，则这个数字为0。
    #第二维从元素1到元素13，表示的是农历每个月的天数。月份是依次排列的
    #如果这一年没有闰月，那么元素13就为0。
    #元素14表示这一农历年的天干，元素15表示地支。
    #只有1900年是一个例外，因为我们从公历1900年12月21日开始计算的，所以
    #这个数组前面都是0。
    private $everymonth=array(
                    0=>array(8,0,0,0,0,0,0,0,0,0,0,0,29,30,7,1),
                    1=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,8,2),
                    2=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,9,3),
                    3=>array(5,29,30,29,30,29,29,30,29,29,30,30,29,30,10,4),
                    4=>array(0,30,30,29,30,29,29,30,29,29,30,30,29,0,1,5),
                    5=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,2,6),
                    6=>array(4,29,30,30,29,30,29,30,29,30,29,30,29,30,3,7),
                    7=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,4,8),
                    8=>array(0,30,29,29,30,30,29,30,29,30,30,29,30,0,5,9),
                    9=>array(2,29,30,29,29,30,29,30,29,30,30,30,29,30,6,10),
                    10=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,7,11),
                    11=>array(6,30,29,30,29,29,30,29,29,30,30,29,30,30,8,12),
                    12=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,9,1),
                    13=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,10,2),
                    14=>array(5,30,30,29,30,29,30,29,30,29,30,29,29,30,1,3),
                    15=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,2,4),
                    16=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,3,5),
                    17=>array(2,30,29,29,30,29,30,30,29,30,30,29,30,29,4,6),
                    18=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,5,7),
                    19=>array(7,29,30,29,29,30,29,29,30,30,29,30,30,30,6,8),
                    20=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,7,9),
                    21=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,8,10),
                    22=>array(5,30,29,30,30,29,29,30,29,29,30,29,30,30,9,11),
                    23=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,10,12),
                    24=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,1,1),
                    25=>array(4,30,29,30,29,30,30,29,30,30,29,30,29,30,2,2),
                    26=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,3,3),
                    27=>array(0,30,29,29,30,29,30,29,30,29,30,30,30,0,4,4),
                    28=>array(2,29,30,29,29,30,29,29,30,29,30,30,30,30,5,5),
                    29=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,6,6),
                    30=>array(6,29,30,30,29,29,30,29,29,30,29,30,30,29,7,7),
                    31=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,8,8),
                    32=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,9,9),
                    33=>array(5,29,30,30,29,30,30,29,30,29,30,29,29,30,10,10),
                    34=>array(0,29,30,29,30,30,29,30,29,30,30,29,30,0,1,11),
                    35=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,2,12),
                    36=>array(3,30,29,29,30,29,29,30,30,29,30,30,30,29,3,1),
                    37=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,4,2),
                    38=>array(7,30,30,29,29,30,29,29,30,29,30,30,29,30,5,3),
                    39=>array(0,30,30,29,29,30,29,29,30,29,30,29,30,0,6,4),
                    40=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,7,5),
                    41=>array(6,30,30,29,30,30,29,30,29,29,30,29,30,29,8,6),
                    42=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,9,7),
                    43=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,10,8),
                    44=>array(4,30,29,30,29,30,29,30,29,30,30,29,30,30,1,9),
                    45=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,2,10),
                    46=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,3,11),
                    47=>array(2,30,30,29,29,30,29,29,30,29,30,29,30,30,4,12),
                    48=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,5,1),
                    49=>array(7,30,29,30,30,29,30,29,29,30,29,30,29,30,6,2),
                    50=>array(0,29,30,30,29,30,30,29,29,30,29,30,29,0,7,3),
                    51=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,8,4),
                    52=>array(5,29,30,29,30,29,30,29,30,30,29,30,29,30,9,5),
                    53=>array(0,29,30,29,29,30,30,29,30,30,29,30,29,0,10,6),
                    54=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,1,7),
                    55=>array(3,29,30,29,30,29,29,30,29,30,29,30,30,30,2,8),
                    56=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,3,9),
                    57=>array(8,30,29,30,29,30,29,29,30,29,30,29,30,29,4,10),
                    58=>array(0,30,30,30,29,30,29,29,30,29,30,29,30,0,5,11),
                    59=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,6,12),
                    60=>array(6,30,29,30,29,30,30,29,30,29,30,29,30,29,7,1),
                    61=>array(0,30,29,30,29,30,29,30,30,29,30,29,30,0,8,2),
                    62=>array(0,29,30,29,29,30,29,30,30,29,30,30,29,0,9,3),
                    63=>array(4,30,29,30,29,29,30,29,30,29,30,30,30,29,10,4),
                    64=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,1,5),
                    65=>array(0,29,30,29,30,29,29,30,29,29,30,30,29,0,2,6),
                    66=>array(3,30,30,30,29,30,29,29,30,29,29,30,30,29,3,7),
                    67=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,4,8),
                    68=>array(7,29,30,29,30,30,29,30,29,30,29,30,29,30,5,9),
                    69=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,6,10),
                    70=>array(0,30,29,29,30,29,30,30,29,30,30,29,30,0,7,11),
                    71=>array(5,29,30,29,29,30,29,30,29,30,30,30,29,30,8,12),
                    72=>array(0,29,30,29,29,30,29,30,29,30,30,29,30,0,9,1),
                    73=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,10,2),
                    74=>array(4,30,30,29,30,29,29,30,29,29,30,30,29,30,1,3),
                    75=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,2,4),
                    76=>array(8,30,30,29,30,29,30,29,30,29,29,30,29,30,3,5),
                    77=>array(0,30,29,30,30,29,30,29,30,29,30,29,29,0,4,6),
                    78=>array(0,30,29,30,30,29,30,30,29,30,29,30,29,0,5,7),
                    79=>array(6,30,29,29,30,29,30,30,29,30,30,29,30,29,6,8),
                    80=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,7,9),
                    81=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,8,10),
                    82=>array(4,30,29,30,29,29,30,29,29,30,29,30,30,30,9,11),
                    83=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,10,12),
                    84=>array(10,30,29,30,30,29,29,30,29,29,30,29,30,30,1,1),
                    85=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,2,2),
                    86=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,3,3),
                    87=>array(6,30,29,30,29,30,30,29,30,30,29,30,29,29,4,4),
                    88=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,5,5),
                    89=>array(0,30,29,29,30,29,29,30,30,29,30,30,30,0,6,6),
                    90=>array(5,29,30,29,29,30,29,29,30,29,30,30,30,30,7,7),
                    91=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,8,8),
                    92=>array(0,29,30,30,29,29,30,29,29,30,29,30,30,0,9,9),
                    93=>array(3,29,30,30,29,30,29,30,29,29,30,29,30,29,10,10),
                    94=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,1,11),
                    95=>array(8,29,30,30,29,30,29,30,30,29,29,30,29,30,2,12),
                    96=>array(0,29,30,29,30,30,29,30,29,30,30,29,29,0,3,1),
                    97=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,4,2),
                    98=>array(5,30,29,29,30,29,29,30,30,29,30,30,29,30,5,3),
                    99=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,6,4),
                    100=>array(0,30,30,29,29,30,29,29,30,29,30,30,29,0,7,5),
                    101=>array(4,30,30,29,30,29,30,29,29,30,29,30,29,30,8,6),
                    102=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,9,7),
                    103=>array(0,30,30,29,30,30,29,30,29,29,30,29,30,0,10,8),
                    104=>array(2,29,30,29,30,30,29,30,29,30,29,30,29,30,1,9),
                    105=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,2,10),
                    106=>array(7,30,29,30,29,30,29,30,29,30,30,29,30,30,3,11),
                    107=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,4,12),
                    108=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,5,1),
                    109=>array(5,30,30,29,29,30,29,29,30,29,30,29,30,30,6,2),
                    110=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,7,3),
                    111=>array(0,30,29,30,30,29,30,29,29,30,29,30,29,0,8,4),
                    112=>array(4,30,29,30,30,29,30,29,30,29,30,29,30,29,9,5),
                    113=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,10,6),
                    114=>array(9,29,30,29,30,29,30,29,30,30,29,30,29,30,1,7),
                    115=>array(0,29,30,29,29,30,29,30,30,30,29,30,29,0,2,8),
                    116=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,3,9),
                    117=>array(6,29,30,29,30,29,29,30,29,30,29,30,30,30,4,10),
                    118=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,5,11),
                    119=>array(0,30,29,30,29,30,29,29,30,29,29,30,30,0,6,12),
                    120=>array(4,29,30,30,30,29,30,29,29,30,29,30,29,30,7,1),
                    121=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,8,2),
                    122=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,9,3),
                    123=>array(2,29,30,29,29,30,30,29,30,30,29,30,29,30,10,4),
                    124=>array(0,29,30,29,29,30,29,30,30,29,30,30,29,0,1,5),
                    125=>array(6,30,29,30,29,29,30,29,30,29,30,30,30,29,2,6),
                    126=>array(0,30,29,30,29,29,30,29,29,30,30,30,29,0,3,7),
                    127=>array(0,30,30,29,30,29,29,30,29,29,30,30,29,0,4,8),
                    128=>array(5,30,30,30,29,30,29,29,30,29,29,30,30,29,5,9),
                    129=>array(0,30,30,29,30,29,30,29,30,29,29,30,30,0,6,10),
                    130=>array(0,29,30,29,30,30,29,30,29,30,29,30,29,0,7,11),
                    131=>array(3,29,30,30,29,30,29,30,30,29,30,29,30,29,8,12),
                    132=>array(0,30,29,29,30,29,30,30,29,30,30,29,30,0,9,1),
                    133=>array(11,29,30,29,29,30,29,30,29,30,30,30,29,30,10,2),
                    134=>array(0,29,30,29,29,30,29,30,29,30,30,29,30,0,1,3),
                    135=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,2,4),
                    136=>array(6,30,30,29,30,29,29,30,29,29,30,29,30,30,3,5),
                    137=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,4,6),
                    138=>array(0,30,30,29,30,29,30,29,30,29,29,30,29,0,5,7),
                    139=>array(5,30,30,29,30,30,29,30,29,30,29,30,29,29,6,8),
                    140=>array(0,30,29,30,30,29,30,29,30,30,29,30,29,0,7,9),
                    141=>array(0,29,30,29,30,29,30,30,29,30,30,29,30,0,8,10),
                    142=>array(2,29,30,29,29,30,29,30,29,30,30,29,30,30,9,11),
                    143=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,10,12),
                    144=>array(7,30,29,30,29,29,30,29,29,30,29,30,30,30,1,1),
                    145=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,2,2),
                    146=>array(0,30,29,30,29,30,29,30,29,29,30,29,30,0,3,3),
                    147=>array(5,30,29,30,30,29,30,29,30,29,29,30,29,30,4,4),
                    148=>array(0,29,30,30,29,30,30,29,30,29,29,30,29,0,5,5),
                    149=>array(0,30,29,30,29,30,30,29,30,30,29,30,29,0,6,6),
                    150=>array(3,29,30,29,30,29,30,29,30,30,29,30,30,29,7,7),
                    151=>array(0,30,29,29,30,29,29,30,30,29,30,30,30,0,8,8),
                    152=>array(8,29,30,29,29,30,29,29,30,29,30,30,30,30,9,9),
                    153=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,10,10),
                    154=>array(0,29,30,30,29,29,30,29,29,30,29,30,30,0,1,11),
                    155=>array(6,29,30,30,29,30,29,30,29,29,30,29,30,29,2,12),
                    156=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,3,1),
                    157=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,4,2),
                    158=>array(4,30,29,30,29,30,29,30,30,29,30,30,29,29,5,3),
                    159=>array(0,30,29,30,29,30,29,30,29,30,30,30,29,0,6,4),
                    160=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,7,5),
                    161=>array(3,30,30,29,29,30,29,29,30,29,30,30,30,29,8,6),
                    162=>array(0,30,30,29,29,30,29,29,30,29,30,30,29,0,9,7),
                    163=>array(7,30,30,29,30,29,30,29,29,30,29,30,29,30,10,8),
                    164=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,1,9),
                    165=>array(0,30,30,29,30,30,29,30,29,29,30,29,30,0,2,10),
                    166=>array(5,29,30,29,30,30,29,30,29,30,29,30,29,30,3,11),
                    167=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,4,12),
                    168=>array(0,30,29,30,29,29,30,30,29,30,30,29,30,0,5,1),
                    169=>array(4,29,30,29,30,29,29,30,29,30,30,30,29,30,6,2),
                    170=>array(0,29,30,29,30,29,29,30,29,30,30,29,30,0,7,3),
                    171=>array(8,30,29,30,29,30,29,29,30,29,30,29,30,30,8,4),
                    172=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,9,5),
                    173=>array(0,30,29,30,30,29,30,29,29,30,29,30,29,0,10,6),
                    174=>array(6,30,29,30,30,29,30,29,30,29,30,29,30,29,1,7),
                    175=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,2,8),
                    176=>array(0,29,30,29,30,29,30,29,30,30,29,30,29,0,3,9),
                    177=>array(4,30,29,30,29,29,30,29,30,30,30,29,30,29,4,10),
                    178=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,5,11),
                    179=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,6,12),
                    180=>array(3,30,29,30,29,30,29,29,30,29,29,30,30,30,7,1),
                    181=>array(0,29,30,30,29,30,29,29,30,29,29,30,30,0,8,2),
                    182=>array(7,29,30,30,30,29,29,30,29,30,29,29,30,30,9,3),
                    183=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,10,4),
                    184=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,1,5),
                    185=>array(5,29,30,29,29,30,30,29,30,30,29,30,29,30,2,6),
                    186=>array(0,29,30,29,29,30,29,30,30,29,30,30,29,0,3,7),
                    187=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,4,8),
                    188=>array(4,29,30,29,30,29,29,30,29,29,30,30,30,29,5,9),
                    189=>array(0,30,30,29,30,29,29,29,30,29,30,30,29,0,6,10),
                    190=>array(8,30,30,30,29,30,29,29,30,29,29,30,30,29,7,11),
                    191=>array(0,30,30,29,30,29,30,29,30,29,29,30,29,0,8,12),
                    192=>array(0,30,30,29,30,30,29,30,29,30,29,30,29,0,9,1),
                    193=>array(6,29,30,30,29,30,29,30,30,29,30,29,30,29,10,2),
                    194=>array(0,29,30,29,30,29,30,30,29,30,30,29,30,0,1,3),
                    195=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,2,4),
                    196=>array(4,30,29,30,29,29,30,29,29,30,30,30,29,30,3,5),
                    197=>array(0,30,29,30,29,29,29,30,29,30,30,29,30,0,4,6),
                    198=>array(0,30,30,29,30,29,29,29,30,29,30,29,30,0,5,7),
                    199=>array(2,30,30,29,30,30,29,29,30,29,29,30,29,30,6,8),
                    200=>array(0,30,30,29,30,29,30,29,30,29,29,30,29,0,7,9) 
                    //最后一个数字，是农历二一〇〇年的十二月，我不知道。
                    //好像有个地方写二十九天。我保留这个数字但是请后来人验证之。
                    //谢谢！
                    //因为这个时候已经进入公历2101年了，所以对于这个版本
                    //的万年历，这个数字问题不大。但是毕竟有失严谨。
                    //数据来源：
                    //http://gb.weather.gov.hk/gts/time/conversionc.htm                
                  ); 
  ##############################
    #农历天干
    private $mten=array("null","甲","乙","丙","丁","戊","己","庚","辛","壬","癸");
    #农历地支
    private $mtwelve=array("null","子(鼠)","丑(牛)","寅(虎)","卯(兔)","辰(龙)",
                   "巳(蛇)","午(马)","未(羊)","申(猴)","酉(鸡)","戌(狗)","亥(猪)");
    #农历年
    private $myear=array("〇","一","二","三","四","五","六",
                  "七","八","九","年");
    #农历月份
    private $mmonth=array("闰","正","二","三","四","五","六",
                  "七","八","九","十","十一","十二","月");
    #农历日
    private $mday=array("null","初一","初二","初三","初四","初五","初六","初七","初八","初九","初十",
                "十一","十二","十三","十四","十五","十六","十七","十八","十九","二十",
                "廿一","廿二","廿三","廿四","廿五","廿六","廿七","廿八","廿九","三十");
  ##############################
    #星期
    private $weekday = array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");    
  ##############################
    #计算所给出的日期同公历1900年12月21日之间相差多少天
    #本来想用现成的date_diff程序，发现没有合用的
    #所以只好把原来的写法搬出来。
  private function date_diff($y, $m , $d){
  $today["year"] = $y;
  $today["mon"] = $m;
  $today["mday"] = $d;
  $total = 11;
  for($y=1901;$y<$today["year"];$y++) { //计算到所求日期阳历的总天数-自1900年12月21日始,先算年的和
       $total+=365;
       if ($y%4==0) $total++;
  }

  switch($today["mon"]) { //再加当年的几个月
         case 12:
              $total+=30;
         case 11:
              $total+=31;
         case 10:
              $total+=30;
         case 9:
              $total+=31;
         case 8:
              $total+=31;
         case 7:
              $total+=30;
         case 6:
              $total+=31;
         case 5:
              $total+=30;
         case 4:
              $total+=31;
         case 3:
              $total+=28;
         case 2:
              $total+=31;
  }

  if($today["year"]%4 == 0 && $today["mon"]>2 && $today["year"]!=2100) $total++; 
  //如果当年是闰年还要加一天
  //2100能被一百整除，而不能被四百整除
  $total=$total+$today["mday"]-1; //加当月的天数
  
  return $total;
  }
  ##############################
    #检查时间正确性
  private function checkTime($y, $m, $d){
    if($y < $this->MinYear or $y > $this->MaxYear) die('wrong time');
    if($m < 1 or $m > 12) die('wrong time');
      switch($m) {
         case 12:
              if($d < 1 or $d > 31) die('wrong time');
              return;
         case 11:
              if($d < 1 or $d > 30) die('wrong time');
              return;
         case 10:
              if($d < 1 or $d > 31) die('wrong time');
              return;
         case 9:
              if($d < 1 or $d > 30) die('wrong time');
              return;
         case 8:
              if($d < 1 or $d > 31) die('wrong time');
              return;
         case 7:
              if($d < 1 or $d > 31) die('wrong time');
              return;
         case 6:
              if($d < 1 or $d > 30) die('wrong time');
              return;
         case 5:
              if($d < 1 or $d > 31) die('wrong time');
              return;
         case 4:
              if($d < 1 or $d > 30) die('wrong time');
              return;
         case 3:
              if($d < 1 or $d > 31) die('wrong time');
              return;
         case 2:
              if ($y%4==0 && $y!=2100) if($d < 1 or $d > 29) die('wrong time');
              if ($y%4!=0) if($d < 1 or $d > 28) die('wrong time');
              return;
         case 1:
              if($d < 1 or $d > 31) die('wrong time');
              return;
  }  
  }
  ##############################
    #计算农历日期  
    #这个是public方法
    
    #输入：$y, $m, $d表示要计算的公历日期的年、月、日
    #
    #输出：    
    /*Array ( [year] => 数字农历年
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
    */
  public function getMDate($y, $m, $d){
      $this->checkTime($y, $m, $d);
      #阳历总天数
      $total = $this->date_diff($y, $m, $d);
      #阴历总天数
      $mtotal=0;
      
  $flag1=0;  //判断跳出循环的条件
  $j=0;
  
  //从农历1900年11月初一（即公历1900年12月22日）开始，逐月累加农历
  //每个月的天数。直到总天数超过$total。这时，我们知道，这个月就是
  //我么要求的农历月。
  while ($j<=200){  //用农历的天数累加来判断是否超过阳历的天数
      $i=1;
      while ($i <= 13){
            if ($this->everymonth[$j][$i] != 29 && $this->everymonth[$j][$i] != 30 && $this->everymonth[$j][$i] != 0) die($j.'-'.$i.'-'.'days check wrong.');
            $mtotal += $this->everymonth[$j][$i];
            if ($mtotal >= $total){ 
                 $flag1 = 1;
                 break;
            }
            $i ++;
      }
      if ($flag1 == 1) break;
      $j ++;
  }
  
  if($this->everymonth[$j][0]<>0 && $this->everymonth[$j][0]<$i){
      $mm=$i-1; //闰月
  }
  else{
      $mm=$i;
  }

  if($i==$this->everymonth[$j][0]+1 && $this->everymonth[$j][0]<>0) {
      $nlmon=$this->mmonth[0].$this->mmonth[$mm].$this->mmonth[13];#闰月
      $mm += 100;
  }
  else {
      $nlmon=$this->mmonth[$mm].$this->mmonth[13];
  }
      #以下是简单的中文替换操作
      $inyear = $j + 1900;
      $tmpyear = $inyear;
      $nlyear = '';
      while($tmpyear > 0)
      {
        $nlyear = $this->myear[$tmpyear%10].$nlyear;
        $tmpyear = (int)($tmpyear / 10);
      }
      $nlyear.=$this->myear[10];
      
      $inday = $this->everymonth[$j][$i] - ($mtotal - $total);
      $nlday = $this->mday[$inday];
      
      $inweekday = ($total + 5) % 7;
      $cweekday = $weekday[$inweekday];
      //return $nlyear.$this->mten[$this->everymonth[$j][14]].$this->mtwelve[$this->everymonth[$j][15]].$nlmon.$nlday;
      
      return array(
          "year" => $inyear,
          "cyear" => $nlyear,
          "yearten" => $this->everymonth[$j][14],
          "cyearten" => $this->mten[$this->everymonth[$j][14]],
          "yeartwelve" => $this->everymonth[$j][15],
          "cyeartwelve" => $this->mtwelve[$this->everymonth[$j][15]],
          "month" => $mm,
          "cmonth" => $nlmon,
          "day" => $inday,
          "cday" => $nlday,
          "weekday" => $inweekday,
          "cweekday" => $cweekday
                );
  }  
}
##############################
#主程序结束
?>

<?php
##############################
#测试和示例程序
$a = new s2m();
//$re = $a->getMDate(2001, 3, 12);
//print_r($re);
?>

<html> 

<head> 
<title>世纪万年历</title> 
</head> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<body  text="#008000"> 

<?php
date_default_timezone_set('Asia/Shanghai'); 
#打印年月抬头 
$year = $_REQUEST["year"];
$month = $_REQUEST["month"];
if($year == 0) $year = date("Y");
if($month == 0) $month = date("n");
switch ($month){
         case 1:
         case 3:
         case 5:
         case 7:
         case 8:
         case 10:
         case 12:
              $dd=31;
              break;
         case 4:
         case 6:
         case 9:
         case 11:
              $dd=30;
              break;
         case 2:
              if ($year%4==0 && $year!=2100){
                  $dd=29;
                 }else{
                  $dd=28;
                 }
              break;
       }
echo  '<p  align="center"><font  size="6"><b>'.$year."年".$month.'月</b></font></p>'; 
$firstday = $a->getMDate($year, $month, 1);
$lastday = $a->getMDate($year, $month, $dd);

if  ($firstday['year'] == $lastday['year']){ 
echo  '<p  align="center"><b><font  size="4">'.$firstday['cyearten'].$firstday['cyeartwelve']." 年</font></b></p>"; 
}else{ 
echo  '<p  align="center"><b><font  size="4">'.$firstday['cyearten'].$firstday['cyeartwelve']."/".$lastday['cyearten'].$lastday['cyeartwelve']." 年</font></b></p>"; 
} 
?> 

<div  align="center"> 
<center> 
<table  border="1"  width="85%"> 
<tr> 
<td  align="center"  bgcolor="#CCCCCC"><font  size="4"  color="#FF0000"><b>星期日</b></font></td> 
<td  width="14%"  align="center"  bgcolor="#CCCCCC"><font  color="#000000"  size="4"><b>星期一</b></font></td>
<td  width="14%"  align="center"  bgcolor="#CCCCCC"><font  color="#000000"  size="4"><b>星期二</b></font></td> 
<td  width="14%"  align="center"  bgcolor="#CCCCCC"><font  color="#000000"  size="4"><b>星期三</b></font></td> 
<td  width="14%"  align="center"  bgcolor="#CCCCCC"><font  color="#000000"  size="4"><b>星期四</b></font></td> 
<td  width="14%"  align="center"  bgcolor="#CCCCCC"><font  color="#000000"  size="4"><b>星期五</b></font></td> 
<td  width="14%"  align="center"  bgcolor="#CCCCCC"><font  size="4"  color="#008000"><b>星期六</b></font></td> 


</tr> 
<?php 
$day=1; 
$line=0; 
$k = $firstday['weekday'];
while  ($day <= $dd){ 
  echo  "<tr>"; 
  for  ($s=0;$s<=6;$s++){ 
  if  ($k>0){ 
    //空格 
    echo  '<td  width="14%"  align="center">&nbsp;</td>'; 
    $k--; 
  }else{ 
    if($day <= $dd)
    {
      #设置字符颜色 
      switch  ($s){ 
      case  1: 
      case  2: 
      case  3: 
      case  4: 
      case  5: 
      $color="#000000"; 
      break; 
      case  0: 
      $color="#FF0000"; 
      break; 
      case  6: 
      $color="#008000"; 
      break; 
      } 
      $chicolor = $color;
      #生成中文农历 
      $today = $a->getMDate($year, $month, $day);
      if($today['day'] == 1){$chi = $today['cmonth']; $chicolor = "#0080FF";}
      else $chi = $today['cday'];
      
      echo '<td  width="14%" ';
      if ( ($year==date('Y')) && ($month==date('n')) && ($day==date('j'))  )
      {
        echo 'BGCOLOR="#ffcccc"';
      }
      echo ' align="center"><font><font  size="4" color="'.$color.'"><b>'.$day.'  </b>  </font><b><font  size="2" color="'.$chicolor.'">'.$chi.'</font></b></font></td>'; 
      #下一天 
      $day++; 
    }else
    {#补足空行 
      echo  '<td  width="14%"  align="center">&nbsp;</td>'; 
    }
  } 
  
  } 
  echo  "</tr>"; 
  $line++; 
}
?>
</table> 
</center> 
</div> 

<?php 


echo  "</table>"; 

#打印上一月，下一月 
$ly=$ny=$year; 
$last=$month-1; 
if  ($last==0){ 
$last=12; 
$ly--; 
} 
$next=$month+1; 
if  ($next==13){ 
$next=1; 
$ny++; 
} 
if  ($ly>=$a->MinYear)
echo  '<p  align="center"><a  href="'.$file.'?year='.$ly.'&month='.$last.'"><<上一个月</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 
else 
echo  '<p  align="center">'; 
if  ($ny<=$a->MaxYear) 
echo  '<a  href="'.$file.'?year='.$ny.'&month='.$next.'">下一个月>></a></p>'; 
?> 

<?php 
echo  '<form  method="POST"  action="'.$file.'">'; 
?> 
<p  align="center"><font  color="#000000">年份：</font><select  size="1"  name="year"> 
<?php
for($index = $a->MinYear; $index <= $a->MaxYear; $index ++)
{
  if ($index == date("Y"))
  {
    echo "<option selected>$index</option>";
  } else
  {
    echo "<option>$index</option>";
  }
}
?>
</select><font  color="#000000">年</font><font  color="#000000"> 
月份：<select  size="1"  name="month"> 
<?php
for($index = 1; $index <= 12; $index ++)
{ 
  if ($index == date("n"))
  {
    echo "<option selected>$index</option>";
  } else
  {
    echo "<option>$index</option>";
  }
}
?>
</select>月  </font><input  type="submit"  value="查询"  name="B1"></p>
</form>
</body>
</html>  
