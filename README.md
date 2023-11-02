# racing-api
A sample test task built with Symfony and API Platform

## API Endpoints 
- Basic CRUD operations (Race, RacingData, Placements) using API Platforms are available at `/api` route.

### Save Race with CSV Data
- Endpoint: `/api/race-results/upload-csv`
- Request: POST : Textfield with names ***race_title*** and ***race_date***. File field with name ***csv_data***
- Reponse: code and message json

### Get Races Collection
- Endpoint: `/api/get-races-collection`
- Request: GET (empty request)
- Response: code and message/data json

### Get Results by Race
- Endpoint: `/api/get-race-results/{raceId}`
- Request: GET (with race id in the url)
- Response: code and message/data json
