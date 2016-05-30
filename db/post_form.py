# -*- coding: utf-8 -*-
import urllib, urllib2
page = 'http://students.washington.edu/cyc025/db/insert.php'
order = '3'
items = 'pizza'
quantity = '100'
size = 'small'
details = 'cheese'
price = '12'
raw_params = {'order': unicode(order, "utf-8"),'item': unicode(items, "utf-8"),'quantity':unicode(quantity, "utf-8"), \
'size':unicode(size, "utf-8"),'details':unicode(details, "utf-8"),'price':unicode(price, "utf-8")}
params = urllib.urlencode(raw_params)
request = urllib2.Request(page, params)
page = urllib2.urlopen(request)
info = page.info()
