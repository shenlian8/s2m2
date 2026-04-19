import unittest
import json
from datetime import datetime
from s2m2 import S2M

class TestS2M2(unittest.TestCase):
    def setUp(self):
        self.s2m = S2M()

    def test_basic_conversion(self):
        """测试基础的公历到农历转换"""
        res_json = self.s2m.getMDate(1976, 11, 13)
        res = json.loads(res_json)
        self.assertEqual(res['gregorian_year'], 1976)
        self.assertEqual(res['gregorian_month'], 11)
        self.assertEqual(res['year'], 1976)
        self.assertEqual(res['cyear'], '一九七六年')
        self.assertEqual(res['cyearten'], '丙')
        self.assertEqual(res['cyeartwelve'], '辰(龙)')
        self.assertEqual(res['month'], 9)
        self.assertEqual(res['cmonth'], '九月')
        self.assertEqual(res['cday'], '廿二')
        self.assertEqual(res['cweekday'], '星期六')
        self.assertFalse(res['is_leap_month'])
        
    def test_min_boundary(self):
        """测试区间起点 1901-01-01"""
        res_json = self.s2m.getMDate(1901, 1, 1)
        res = json.loads(res_json)
        self.assertEqual(res['gregorian_year'], 1901)
        self.assertEqual(res['gregorian_month'], 1)
        self.assertEqual(res['gregorian_day'], 1)
        self.assertIn('cweekday', res)
        
    def test_max_boundary(self):
        """测试区间终点 2100-12-31"""
        res_json = self.s2m.getMDate(2100, 12, 31)
        res = json.loads(res_json)
        self.assertEqual(res['gregorian_year'], 2100)
        self.assertEqual(res['gregorian_month'], 12)
        self.assertEqual(res['gregorian_day'], 31)
        
    def test_empty_arguments(self):
        """测试不传参数时使用系统当前日期"""
        res_json = self.s2m.getMDate()
        res = json.loads(res_json)
        now = datetime.now()
        self.assertEqual(res['gregorian_year'], now.year)
        self.assertEqual(res['gregorian_month'], now.month)
        self.assertEqual(res['gregorian_day'], now.day)
        
    def test_partial_arguments(self):
        """测试部分空参数的情况（应抛出异常）"""
        with self.assertRaisesRegex(ValueError, "Parameters must be all None or all provided"):
            self.s2m.getMDate(2020, 5) # missing day
        with self.assertRaisesRegex(ValueError, "Parameters must be all None or all provided"):
            self.s2m.getMDate(2020, d=5) # missing month
            
    def test_invalid_dates_logic(self):
        """测试不存在的日期逻辑（例如平年2月29日）"""
        with self.assertRaisesRegex(ValueError, "输入了非法的公历日期"):
            self.s2m.getMDate(2023, 2, 29) 
            
    def test_out_of_bounds_dates(self):
        """测试超出合法支持范围的日期"""
        # 测试小于1901年
        with self.assertRaisesRegex(ValueError, "date out of range"):
            self.s2m.getMDate(1900, 12, 31)
            
        # 测试大于2100年
        with self.assertRaisesRegex(ValueError, "date out of range"):
            self.s2m.getMDate(2101, 1, 1)

if __name__ == '__main__':
    unittest.main()
