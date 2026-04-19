---
name: Lunar Calendar Converter
description: 从公历（阳历）日期转换为中国农历日期
---

# Lunar Calendar Converter (公历转农历技能)

当用户询问有关农历日期、天干地支、生肖或查询某一天对应的中国农历日期（1901年 - 2100年）时，请使用此技能。

## 执行方法 (How to Use)

工作区根目录下有一个已经封装好的原生 Python 万年历程序 `s2m2.py`。你可以通过底层 `run_command` 工具运行它，以获取准确的 JSON 返回结果。

### 1. 转换指定日期
用 Python 一句话命令调用底层的 `S2M` 类：
```bash
python -c "import json; from s2m2 import S2M; res = json.loads(S2M().getMDate(2024, 2, 10)); print(json.dumps(res, indent=4, ensure_ascii=False))"
```
> **注意**：你需要替换命令中的 `2024, 2, 10` 为用户所要求的具体年、月、日。

### 2. 获取今天的农历日期
如果用户没有指定日期（例如“今天的农历是多少？”），你可以留空参数来默认获取今日数据：
```bash
python -c "import json; from s2m2 import S2M; res = json.loads(S2M().getMDate()); print(json.dumps(res, indent=4, ensure_ascii=False))"
```

### 3. 农历反推公历 (逆向查询)
如果用户给出的是一个**农历日期**，要求你算出它对应的公历是哪一天（例如“1995年闰八月十五对应的阳历是哪天？”）：
你可以使用底层的 `getGregorianDate(l_year, l_month, l_day, is_leap_month=False)` 方法：
```bash
python -c "import json; from s2m2 import S2M; res = json.loads(S2M().getGregorianDate(1995, 8, 15, is_leap_month=True)); print(json.dumps(res, indent=4, ensure_ascii=False))"
```
> **注意**：传给 `getGregorianDate` 的参数必须是纯数字（年份、月份1-12、日期1-30）。如果用户强调是**闰月**，必须将 `is_leap_month=True` 传入！返回值依然是那个包含了所有公历、农历和干支信息的完整大 JSON。

## 返回字段说明 (Output Definitions)

调用该脚本后，系统会返回标准的 JSON 结构，你可以使用其中的对应中文字段来为用户提供生动易读的回复。
核心数据结构如下：
* `cmonth` + `cday` : 中文农历月日（例如 "正月初一"）
* `cyear` : 中文农历年份（例如 "二〇二四年"）
* `cyearten` + `cyeartwelve` : 中文干支和生肖（例如 "甲辰(龙)"）
* `is_leap_month`: 是否是闰月运算结果 (true / false)
* `cweekday`: 中文星期几 (例如 "星期六")

## 异常处理
- 对于不支持的年份（小于 1901 或 大于 2100），或者不存在的月份/日期（如平年的2月29日），底层代码将抛出 `ValueError: wrong time`。请向用户委婉解释它不在日历的支持或存在范围内。
