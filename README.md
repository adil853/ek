<p align="center"><a>ekar</a></p>



## About Project

Requirements

- Docker dekstop app 4.11.0 (83626) (you need this on your machine as dev if you are planning to run this via docker command)
- PHP 8.2.7
- Laravel Framework 10.13.5
- Composer version 2.5.8 2023-06-09 17:13:21
- sqlite has been used as database

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

### Dockerized (Suggested way to use)

Note: It is assumed that you have installed a valid docker dekstop app or some other tool which helps running docker on local machine.

To run this project on docker, you simply need docker installed on your machine
and run following command

    go into root directory of project

    docker-compose up --build --force-recreate

This will start serving the project on localhost:12000. As a dev you don't need to get
worried about any dependency etc. You simple need to run this command and start
using exposed endpoint.

### Local

- Navigate to project root directory
- Run "composer install"
- Run "cp .env.example .env"
- Replace DB_DATABASE with absolute path of your sqlite db in .env file i.e. (/var/www/html/database/database.sqlite) (suggested way) or provide complete mysql connection of your local machine you are running app on
- Run "php artisan key:generate"
- Run "php artisan migrate"
- Run "php artisan serve --port=12000"



## Functionalities

This project exposes two end points only
- localhost:12000/api/breakTime (Post)
  - sample curl request is attached
    curl --location 'localhost:12000/api/breakTime' \
    --header 'Content-Type: application/json' \
    --data '{
    "start_time":"2020-03-12 00:10:22",
    "end_time":"2020-03-16 00:11:01",
    "time_expressions":["2m","1d", "2h", "3s"]
    }'
  - Header must have Content-Type:application/json
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
- localhost:12000/api/searchBreakTime (Post)
  - sample curl request is attached
    curl --location 'localhost:12000/api/searchBreakTime' \
    --header 'Content-Type: application/json' \
    --data '{
    "start_time":"2020-03-12 00:10:22",
    "end_time":"2020-03-16 00:11:01"
    }'
  - Header must have Content-Type:application/json
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

## Journal
As this programs logic can be bit trickier then it seems to be but following is the way
it has been resolved. User will pass two timestamps and an expressions of time literals
i.e.

    {
    "start_time":"2020-01-05 12:00:01",
    "end_time":"2020-03-14 00:00:11",
    "time_expressions":["2m","1d", "2h", "3s"]
    }

First of all we do basic validations of expected types and don't allow any duplicate value 
in time_expressions. Then time expressions are sorted in descending order. Then we start 
extracting the time literal according to required time expressions. i.e. 

if difference in seconds in both time is assume 6000000, and assume one month contains
2592000 and we are looking for 2m expression we will apply following formula

        6000000/(2592000*2) = 1.1574
        as 1.1574 >= 1
        so its 1 unit of 2m and now remaing time is
        (1.1574-1)*2592000*2 = 815999.99
        now 815999.99 is the time remaing for next expressions after month
        we will iterate through all experrsions and keep apply this fromula.
        and this will give us our answer

# Note: Please note I have assumed that each month consists of 30 days regardless of actual calender as mentioned in assigment.





