# Magento / PHP Technical Exercise

## Implementation - Summery

Hello, I am John Fonseka and this is my solution to the task given. However, There is a
slight difference to the command. I couldn't find a way to get nameless arguments. Hence, please find
the below command to get the solution work.

```
bin/magento customer:import -p sample-csv -f sample.csv
bin/magento customer:import -p sample-json -f sample.json
```
In here `-p` is for **profile** and `-f` is for **file**.

### Profile Management

I have implemented the solution in such a way that adding a new class for a new profile with existing structure will
allow developers to extend the plugin with new profiles.

* Profiles **MUST** implement ``/Profile/ProfileInterface``. (This is to follow a common pattern to get data and checking instance type.)
* Return of the ``getData()`` function must follow the given structure. (Plugin depends on return data format from profile classes)
* Expected return type of ``getData()`` is  
  ```
  'fname' => 'John',  
  'lname' => 'Doe',  
  'emailaddress' => 'someone@example.com',  
  'pass' => 'UserPassWord'  
  ```
* Profile class name should match the expected pattern (profile name in command : `simple-csv`, then class should be ``/Profile/SimpleCsv``) .

This way we don't have to actually edit(modify) the existing files, but just adding a correct file will add a new profile.

### Improvements
* Can write a profile management base class with common functions to be extended when implementing profilers. 
* Can break down getData() function for better testability and for better SOLID. (retrieve data from source, validate data, format data to common pattern etc)

### Time taken for implementation

It took me about 8 hours to finish this plugin despite 4 hour time limit.

### Software Versions I have used

* PHP version : 7.4
* Magento version : 2.4.3

## Installation Guide

Steps I followed to install and test the plugin in a vanilla Magento 2.4.3-p1 installation as follows

1. ``` composer require johnfonseka/customer-import ```
2. ``` php bin/magento setup:upgrade ``` This is not required. Just a habit when installing a new module.
3. ``` php bin/magento s:d:c ``` Required step. In order to execute di.xml in the plugin
4. ``` php bin/magento s:s:d -f ``` Again not really required.
5. Then we can run the plugin command.
```
php bin/magento customer:import -p sample-csv -f /Users/johnfonseka/Downloads/Ex_Test_WTC/sample.csv
php bin/magento customer:import -p sample-json -f /Users/johnfonseka/Downloads/Ex_Test_WTC/sample.json
```
