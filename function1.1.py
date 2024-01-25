# -*- coding: utf-8 -*-
"""
Created on Sun Jan 21 17:49:34 2024

@author: brryanh365
"""

import networkx as nx
import matplotlib.pyplot as plt
import pymysql

# 資料庫設定
db_settings = {
    "host": "localhost",
    "port": 8080,
    "user": "root",
    "password": "12345678",
    "db": "data",
    "charset": "utf8"
}
try:
    
    # 建立Connection物件
    conn = pymysql.connect(**db_settings)
    # 建立Cursor物件
    with conn.cursor() as cursor:
        # 查詢資料SQL語法
        command = "SELECT 要保人序號 FROM data"
        # 執行指令
        cursor.execute(command)
        # 取得所有資料
        a = cursor.fetchmany(20)
        
        command = "SELECT 被保人序號 FROM data"
        # 執行指令
        cursor.execute(command)
        # 取得所有資料
        b = cursor.fetchmany(20)
                
        edges = list(zip(a, b))
 
        B = nx.DiGraph()
        B.add_edges_from(edges)
 
        options = {"with_labels": False, "node_color": "white", "edgecolors": "blue"}
 
        fig = plt.figure(figsize=(27, 30))
        axgrid = fig.add_gridspec(3, 2)
 
        ax1 = fig.add_subplot(axgrid[0, 0])
        ax1.set_title("Bayesian Network")
        
        
        
        pos = nx.nx_agraph.graphviz_layout(B, prog="neato")
        nx.draw_networkx(B, pos=pos, **options)
        nx.draw_networkx_labels(B, pos, labels={}, font_size=0)
 
 
        plt.tight_layout()
        plt.show()

        
        
except Exception as ex:

    print(ex)
    
    
finally:
    # 記得關閉資料庫連接
    conn.close()