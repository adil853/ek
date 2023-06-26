<p align="center"><a>ekar</a></p>



## About Project

Requirements
- PHP 8.2.7
- Laravel Framework 10.13.5
- Composer version 2.5.8 2023-06-09 17:13:21

Purpose
    This is a simple project based on Laravel. It simply exposed two end point.
    At one end point user will provide two time stamps and an array of expressions of 
    time expressions and this will return breakdown of time is required expressions.
    The second end point also expects two time stamps but it returns history of those 
    timestamps.


## How to run project

This project can be run by two ways. 
- Dockerized
- Local

### Dockerized

To run this project on docker, you simply need docker installed on your machine
and run following command

    docker-compose up --build --force-recreate

This will start serving the project on localhost:12000. As a dev you don't need to get
worried about any dependency etc. You simple need to run this command and start
using exposed endpoint.

### Local

- Navigate to project directory
- Run "composer install"
- Run "cp .env.example .env" (replace DB_DATABASE with absolute path of your sqlite db)
- Run "php artisan key:generate"
- Run "php artisan migrate"
- Run "php artisan serve --port=12000"



## Functionalities

This project exposes two end points only
- localhost:12000/api/breakTime (Post)
  - It expects json as request body with following structure
    - {
      "start_time":"2020-01-05 12:00:01",
      "end_time":"2020-03-04 00:00:11",
      "time_expressions":["1m","1d", "2h", "3s"]
      }
  - Response can be of two types
    - Success
      - {
        "message": "Key in following json is unit name and value is its weightage",
        "data": {
        "1m": "1.00",
        "1d": "28.00",
        "2h": "6.00",
        "3s": "3.33"
        }
        }
    - Validation fail
      - {
        "errors": {
        "end_time": [
        "The end time field must match the format Y-m-d H:i:s."
        ]
        }
        }
- localhost:12000/api/breakTime (Get)
  - It expects json as request body with following structure
  - {
    "start_time":"2020-01-05 12:00:01",
    "end_time":"2020-03-04 00:00:11"
    }
  - - Response can be of two types
  - Success
      - [
        {
        "id": 26,
        "start_time": "2020-01-05 12:00:01",
        "end_time": "2020-03-14 00:00:11",
        "time_expressions": {
        "2m": "1.00",
        "1d": "8.00",
        "2h": "6.00",
        "3s": "3.33"
        },
        "created_at": "2023-06-26T14:37:48.000000Z",
        "updated_at": "2023-06-26T14:37:48.000000Z"
        }
        ]
  - Validation fail
      - {
        "errors": {
        "start_time": [
        "The start time field must match the format Y-m-d H:i:s."
        ]
        }
        }



