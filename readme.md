<h1>SWAGGIER</h1>
<h2>Description</h2>
It builds OpenApi 3.0.0 documentation by sending request to selected RestApi.<br>
Works only with JSON format (Response) and provides GET, POST, PUT, PATCH and DELETE http request.

<h2>Usage</h2>
<h3>Basic</h3>
Fist you have to build json with request schema with looks like this<br>
```JSON
[
  {
    "url": "https://localhost:3000",
    "endpoint": "/test/{id}",
    "method": "get",
    "headers": [
      "Content-type: application/json"
    ],
    "parameters": {
      "id": 1
    }
  },
  {
    "url": "https://localhost:3000",
    "endpoint": "/test",
    "method": "get",
    "headers": [
      "Content-type: application/json"
    ]
  },
  {
    "url": "https://localhost:3000",
    "endpoint": "/test",
    "method": "post",
    "headers": [
      "Content-type: application/json"
    ],
    "request": {
      "name": "Marek",
      "surname": "Jastrzebski",
      "age": 33,
      "luckyNumber": 0.23,
      "others": {
        "hobby" : ["reading", "cleaning"],
        "programingLangs": {
          "php": true,
          "Java": false
        },
        "badname": "phr"
      }
    }
  }
]
```
<h4>Request Elements</h4>

<h5>url</h5>
Please pass url w/o endpoint and no slash at the end of address.
<h5>endpoint</h5>
Please pass endpoint, if you want to pass any parameter please set its name in { }.
<h5>method</h5>
Please set method that you want to use to send this request.
<h5>headers</h5>
Please pass all headers that you want to send with request. Always set it as an array.
<h5>parameters (optional)</h5>
Please set this element only when you want to pass any parameter in query(url). Names have to be same like in query.
<h5>request (optional)</h5>
Please pass JSON request body 

<h3>Run</h3>
If you want to run strict request from your request.json use this command
```shell
bin/swaggier -r=request.json
```
It will output swaggier.json file that contains OpenApi 3.0 description.
<br></br>
But if you want to run some alternative scenarios that can generate other responses
```shell
bin/swaggier -r=request.json -b=true
```

It will run scenarios "Original", "No Headers", "No Request", "Type Juggling"

