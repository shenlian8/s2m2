# 世纪万年历 (s2m2)

![s2m2](https://cloud.githubusercontent.com/assets/9590431/6704681/4b013362-cd4c-11e4-9139-d6f97e4e91a5.jpg)

**This program shows how to convert Gregorian Calendar dates to Chinese Lunar Calendar dates.**
**支持的公历范围 / The range of the input is from 1901-01-01 to 2100-12-31**

[🔗 线上演示 Demo](http://shenban.de/s2m/)

本项目最初用 PHP 编写，现提供 **PHP** 与 **Python** 两个语言版本的核心类库。同时为了适应大模型时代，项目内置了给 AI Agent 环境预挂载的**专门技能（Agent Skills）**配置文件。

---

## 🐍 Python 版本环境 (`s2m2.py`)

提供通过现代标准库高度优化、完全重构的 Python 版本。返回规范的 JSON 数据格式，具有严密的异常拦截。

### 使用方法 (Usage)

```python
from s2m2 import S2M
import json

s2m = S2M()

# 返回传入日期的 JSON 格式农历结果
result = s2m.getMDate(1976, 11, 13) 
print(json.dumps(json.loads(result), indent=4, ensure_ascii=False))

# 不传任何参数时，默认获取操作系统今天的农历日期
today_result = s2m.getMDate() 
```

### JSON 返回格式含义 (Output Definition)

```json
{
    "gregorian_year": 1976,  // 数字公历年
    "gregorian_month": 11,   // 数字公历月
    "gregorian_day": 13,     // 数字公历日
    "year": 1976,            // 数字农历年
    "cyear": "一九七六年",   // 中文农历年
    "yearten": 3,            // 数字天干
    "cyearten": "丙",        // 中文天干
    "yeartwelve": 5,         // 数字地支
    "cyeartwelve": "辰(龙)", // 中文地支
    "month": 9,              // 数字农历月（与PHP不同：闰月直接拆分在 is_leap_month，不再 +100）
    "is_leap_month": false,  // boolean：该月是否是闰月
    "cmonth": "九月",        // 中文农历月
    "day": 22,               // 数字农历日
    "cday": "廿二",          // 中文农历日
    "weekday": 6,            // 数字星期日期，0 为星期天
    "cweekday": "星期六"      // 中文星期日期
}
```

---

## 🐘 PHP 版本环境 (`s2m2.php`)

项目的原版。目前已使用现代 PHP 的 `DateTime` 处理机制进行优化加速并修复了隐患。

### 使用方法 (Usage)

```php
$a = new s2m();
$re = $a->getMDate(2001, 3, 12);
print_r($re);
```

### Array 返回格式含义 (Output Definition)

```php
Array ( 
    [year] => 数字农历年
    [cyear] => 中文农历年 
    [yearten] => 数字天干
    [cyearten] => 中文天干
    [yeartwelve] => 数字地支
    [cyeartwelve] => 中文地支
    [month] => 数字农历月，⚠️ 闰月用1xx的形式表示，比如闰5月为 105
    [cmonth] => 中文农历月
    [day] => 数字农历日
    [cday] => 中文农历日
    [weekday] => 数字星期日期，0为星期天
    [cweekday] => 中文星期日期
) 
```

---

## 🤖 关于 AI Agent 技能集成

项目中提供了一个为 AI 本地编程助手准备的元数据挂载点，位于隐藏文件夹 `.agents/skills/lunar_calendar/SKILL.md` 中。
这使得挂接该项目的 AI Agent（如 Antigravity）具备**公历转农历技能**。你可以直接在对话框里对 AI 下达日常自然语言：“帮我查查1999年正月初五的八字”或者“测试一下明天的农历”，AI 就能利用预载能力无缝在后台调取工具算给你听。
