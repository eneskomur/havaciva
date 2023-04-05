<?php
class Havaciva
{
    public $appid = "APPID";
    public $cities = array("adana", "adıyaman", "afyon", "ağrı", "amasya", "ankara", "antalya", "artvin", "aydın", "balıkesir", "bilecik", "bingöl", "bitlis", "bolu", "burdur", "bursa", "çanakkale", "çankırı", "çorum", "denizli", "diyarbakır", "edirne", "elazığ", "erzincan", "erzurum", "eskişehir", "gaziantep", "giresun", "gümüşhane", "hakkâri", "hatay", "ısparta", "mersin", "istanbul", "izmir", "kars", "kastamonu", "kayseri", "kırklareli", "kırşehir", "kocaeli", "konya", "kütahya", "malatya", "manisa", "kahramanmaraş", "mardin", "muğla", "muş", "nevşehir", "niğde", "ordu", "rize", "sakarya", "samsun", "siirt", "sinop", "sivas", "tekirdağ", "tokat", "trabzon", "tunceli", "şanlıurfa", "uşak", "van", "yozgat", "zonguldak", "aksaray", "bayburt", "karaman", "kırıkkale", "batman", "şırnak", "bartın", "ardahan", "ığdır", "yalova", "karabük", "kilis", "osmaniye", "düzce");
    public $cache_min = 120;

    public function fetchWeather($city)
    {
        if (!in_array($city, $this->cities)) {
            return false;
        }
        $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&units=metric&appid=" . $this->appid . "&lang=tr";

        if (($json = @file_get_contents($url)) === false) {
            return false;
        }

        $data = json_decode($json, true);
        $city_data = array(
            'name' => $data['name'],
            'temp' => $data['main']['temp'],
            'temp_min' => $data['main']['temp_min'],
            'humidity' => $data['main']['humidity'],
            'pressure' => $data['main']['pressure'],
            'wind_speed' => $data['wind']['speed'],
            'wind_deg' => $data['wind']['deg'],
            'description' => $data['weather'][0]['description'],
            'icon' => $data['weather'][0]['icon'],
            'date' => time(),
            'human_date' => date('d.m.Y H:i:s'),
        );
        $data_file = json_decode(file_get_contents('weather.json'), true);
        $data_file[$city] = $city_data;
        $json_file = json_encode($data_file);
        file_put_contents('weather.json', $json_file);
        return $city_data;
    }

    public function getWeather($city)
    {
        if (!in_array($city, $this->cities)) {
            return false;
        }
        $data_file = json_decode(file_get_contents('weather.json'), true);

        if (isset($data_file[$city])) {
            $city_data = $data_file[$city];
            if ($city_data['date'] < (time() - ($this->cache_min * 60))) {
                $new_data = $this->fetchWeather($city);
                if (true || !isset($new_data['date'])) {
                    return false;
                }
            } else {
                return $city_data;
            }
        } else {
            return $this->fetchWeather($city);
        }
    }
}
