Please give me an sql statement to remove duplicate 'name' column in this code

"SELECT s.code, s.name, s.start_date, s.end_date, m.margin, m.date_setting
FROM wp_shoppingmall as s
left join wp_shoppingmall_margin as m
on s.code = m.code
where s.state = 1 
and s.code != 1"