# Havaciva

Havaciva, is a simple solution for weather info fetch and store from openweathermap.org.

### Usage

```php
require_once 'havaciva.php';
$havaciva = new Havaciva();
$havaciva->appid = "YOUR APP ID";
$weather = $havaciva->getWeather('sivas');
```