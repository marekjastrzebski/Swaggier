# SWAGGIER

## Install
```shell
composer require jastrzebski/swaggier
```
## Description
It builds OpenApi 3.0.0 documentation by sending request to selected RestApi.
Works only with JSON format (Response) and provides GET, POST, PUT, PATCH and DELETE http request.

## Usage
### Basic
Fist you have to build json with request schema with looks like this
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
### Request Elements

#### url
Please pass url w/o endpoint and no slash at the end of address.
#### endpoint
Please pass endpoint, if you want to pass any parameter please set its name in { }.
##### method
Please set method that you want to use to send this request.
#### headers
Please pass all headers that you want to send with request. Always set it as an array.
#### parameters (optional)
Please set this element only when you want to pass any parameter in query(url). Names have to be same like in query.
#### request (optional)
Please pass JSON request body 

## Run
If you want to run strict request from your request.json use this command
```shell
vendor/bin/swaggier -r=request.json
```
It will output swaggier.json file that contains OpenApi 3.0 description.
<br></br>
But if you want to run some alternative scenarios that can generate other responses
```shell
vendor/bin/swaggier -r=request.json -b=true
```

It will run scenarios "Original", "No Headers", "No Request", "Type Juggling"

