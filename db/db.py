#!/usr/bin/env python
# -*- coding: utf-8 -*- 

import MySQLdb

def cgi_handle():
   # Import modules for CGI handling 
   import cgi, cgitb 

   # Create instance of FieldStorage 
   form = cgi.FieldStorage() 

   # Get data from fields
   if form.getvalue('textcontent'):
      text_content = form.getvalue('textcontent')
   else:
      text_content = "Not entered"

   print "Content-type:text/html\r\n\r\n"
   print "<html>"
   print "<head>";
   print "<title>Text Area - Fifth CGI Program</title>"
   print "</head>"
   print "<body>"
   print "<h2> Entered Text Content is %s</h2>" % text_content
   print "</body>"

def checkTableExists(dbcon, tablename):
   stmt = "SHOW TABLES LIKE '"+ tablename + "' ;"
   cursor.execute(stmt)
   return cursor.fetchone()

def connectDB():
   db = MySQLdb.connect(host="127.0.0.1",    # your host, usually localhost
                        user="root",         # your username
                        passwd="ernie",  # your password
                        db="ordering_system",
                        port=8685)        # name of the data base

def createTable():
   # prepare a cursor object using cursor() method
   cursor = db.cursor() 
   if checkTableExists(db,'pizza'):
      # Create table as per requirement
      sql = """ CREATE TABLE PIZZA (
               ITEM  CHAR(20) NOT NULL,
               QUANTITY  CHAR(20),
               SIZE  CHAR(20),
               DETAILS  CHAR(20),
               LAST_NAME  CHAR(20),
               PRICE  CHAR(20) ) """
      cursor.execute(sql)

def disconnectDB():
   # disconnect from server
   db.close()

if __name__ == "__main__":
   #connectDB()
   #createTable()
   #disconnectDB()
   cgi_handle()







