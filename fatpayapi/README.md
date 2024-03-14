# FatPayAPI

## Installation

### Download API

   Click on the Code button then Download ZIP and place the downloaded FatPayAPI folder in a project.

### Install dependencies with composer

   Navigate via CLI to the FatPayApPI folder.

   Use this command to install the necassary dependencies:
   
   `composer install`

## Documentation

### GET Request

Get all transaction by calling "pathToFatPayApiFolder/fatpayapi".
Get one specific transaction by calling "pathToFatPayApiFolder/fatpayapi/ID".

`ID = The ID of the transaction you want to get.`

`pathToFatPayApiFolder = The URL to the folder. For example: "http://localhost/".`

### POST Request

Add a transaction by sending a POST request to "pathToFatPayApiFolder/fatpayapi/" with the request parameter "**lastname**" and "**firstname**".

`pathToFatPayApiFolder = The URL to the folder. For example: "http://localhost/".`

`lastname = The lastname which is saved with the transaction.`

`firstname (optional) = The firstname which is saved with the transaction.`

## Return

All data is returned in json format. GET requests return one/all transactions. POST requests return a status, a errormessage and an id of the inserted transaction.

The status is allways "APPROVED" except an error occuress or the lastname is "Failed", then the status is "ERROR". Check errormessage for more details about the error.
