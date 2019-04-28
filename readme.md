#### prerequisites
install docker and docker-compose

#### Installation 
 1- clone the project
 
    $ git clone https://github.com/abdelrahmanbadr/Business-Days
    
2- run the following command `make init` (this command will build , make host for the app, up docker-compose in background,
composer install, change permission for storage and public folder and finally will copy .env.example to .env)

You can now access the solution from the browser via: `http://business-days.local:8090/api/v1/businessDates`

Notice: in case it did not work you'll just need to update your hosts file `/etc/hosts` with `127.0.0.1 business-days.local`
you can replace `127.0.0.1` with your docker host machine ip.

#### API Usage:
1- `http://business-days.local:8090/api/v1/businessDates` it can be used through Get or Post http method.

Payload :
`{
   "initialDate": "2018-12-12T10:10:10Z",
   "delay": 3
 }
`
or 
`http://business-days.local:8090/api/v1/businessDates?initialDate=2018-12-12T10:10:10Z&delay=3`

Output:

`{
   "businessDate": "2018-12-14T10:10:10Z",
   "totalDays": 3,
   "holidayDays": 0,
   "weekendDays": 0
 }
`

2- `http://business-days.local:8090/api/v1/isBusinessDay/{date}` Get method only and return true or false

Example : `http://business-days.local:8090/api/v1/isBusinessDay/2018-12-12T10:10:10Z` will return true
#### Project structure
- Domain : The domain layer is the heart of the software, and this is where the interesting stuff happens.
- Contracts : Has all interfaces of business days domain.
- Models : Has all entity models of business days domain.
- Hydrators : Used to move data from one place to another, extracting data from object or filling object with data.
- Services :  Services  used to hide and encapsulate App Logic.
- Transformers :  Used to transform the response to match the business need.

#### These are the other available command you might need in the future
- stop all containers `make stop_all_containers`

- remove all containers `make clear_containers`

- remove all images `make clear_images`

#### Running Unit tests:
    $ make phpunit_test
 
#### Improvments(@todo):
    1- Better structure for json file for better searching.
    2- Cache holidays json file in Redis.
    3- Add more countries hoildays and switch between them using config.
    4- Add more logs to trace errors
    5- Write more test cases to increase code coverage
    6- Add more validations for the api request
    7- Use RabbitMQ for Pub/Sub 
   
## Business Logic :
1- Holidays dates exists in storage/data/holidays.json

2- Read the file and hydrate it to Holiday model.

3- Calculate business days from the initial day and the delay then return the business date , weekendDays and holidayDays.

4- Transform the data to the required response.

5- For more info about the domain https://upsource.scratchpay.com/snippets/12
#### Notes:
1- To add more holidays, you can add them in storage/data/holidays.json because it only include usa holidays
for 2019 and 2020 only.

2- Country holidays and weekend days are configurable in .env file.
(BUSINESS_COUNTRY for country code, WEEKEND_DAYS for weekend days codes from monday which is 1 to monday which is 7 )
    
3- There is a default value for BUSINESS_COUNTRY which is USA and WEEKEND_DAYS which is 1,7 (sunday and monday).

