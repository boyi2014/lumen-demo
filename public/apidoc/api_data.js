define({ "api": [
  {
    "type": "GET",
    "url": "/getUpToken",
    "title": "[获得文件上传upToken]",
    "description": "<p>获得文件上传upToken</p>",
    "group": "System",
    "permission": [
      {
        "name": "none"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "fileName",
            "description": "<p>文件名:png|jpeg|jpg</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "hash",
            "description": "<p>文件内容的Hash值，由七牛Hash算法提供</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fileType",
            "description": "<p>文件类型</p>"
          }
        ]
      }
    },
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "status",
            "description": "<p>[响应状态码：0 ]</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>[响应消息]</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": "<p>[响应数据]</p>"
          },
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "fileExist",
            "description": "<p>[文件是否存在，用于避免重复上传]</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "upToken",
            "description": "<p>[七牛上传用的token]</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "urlPreview",
            "description": "<p>[图片预览链接]</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "urDownload",
            "description": "<p>[图片下载链接]</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "fileInfo",
            "description": "<p>[图片信息：图片的长宽大小等信息]</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response",
          "content": "HTTP/1.1 200 文件已存在，并且内容相同，客户端不需要重复上传\n{\n\t\"status\": 0,\n   \t\"message\": \"\", \n   \t\"data\": \n   \t{\n   \t\t\"fileExist\":\"true\",\n   \t\t\"upToken\":\"8bLqz-KeWiZ1mVJKV2Eg6lak2u0NTxmtFUzUUH-A:I32qGKwSi9r5BmqnnHHtVstgNuI=:eyJzY29wZSI6InByZXR0eWltYWdlczpxcS5wbmciLCJkZWFkbGluZSI6MTQ5MDk0NjYxNSwidXBIb3N0cyI6WyJodHRwOlwvXC91cC5xaW5pdS5jb20iLCJodHRwOlwvXC91cGxvYWQucWluaXUuY29tIiwiLUggdXAucWluaXUuY29tIGh0dHA6XC9cLzE4My4xMzYuMTM5LjE2Il19\",\n   \t\t\"urlPreview\":\"http://pic.maimengjun.com/qq.png\",\n   \t\t\"urDownload\":\"http://pic.maimengjun.com/qq.png?e=1490946615&token=8bLqz-KeWiZ1mVJKV2Eg6lak2u0NTxmtFUzUUH-A:IHIwveSH4ghsHnjZku4dnJ4EUp8=\",\n   \t\t\"fileInfo\":\n   \t\t{\n   \t\t\t\"fsize\":2089,\n   \t\t\t\"hash\":\"FnA-Qsi8ik0mF2PrCZZ5zRjYwiko\",\n   \t\t\t\"mimeType\":\"image\\/png\",\n   \t\t\t\"putTime\":1.490874954757e+16,\n   \t\t\t\"fwidth\":47,\n   \t\t\t\"fheight\":47\n   \t\t}\n   \t}\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Integer",
            "optional": false,
            "field": "status",
            "description": "<p>[响应状态码：1]</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>[错误消息]</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response",
          "content": "HTTP/1.1 200 参数缺失\n{\n\t\"status\": 1,\n\t\"message\": \"参数缺失：文件名必填\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/V1/SystemController.php",
    "groupTitle": "System",
    "name": "GetGetuptoken",
    "sampleRequest": [
      {
        "url": "http://maimeng-blog.appzd.net/getUpToken"
      }
    ]
  }
] });
