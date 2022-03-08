'use strict';

class weatherTokio {


    getJSON(url, callback) {
        let xhr = new XMLHttpRequest();
        xhr.onload = () => {
            callback(JSON.parse(xhr.response));
        }
        xhr.open("GET", url);
        xhr.send();
    }

    weather(url) {
        let weather_div = document.getElementById("weatherTokio");
        weather_div.innerHTML = "<ul id='weather-ul'></ul>";
        let seznam_ul = document.getElementById("weather-ul");

        this.getJSON(url, (data) => {
            for (let weather1 of data.weather) {
                let main = document.createElement("li");
                let description = document.createElement("li");
                main.innerText = weather1.main;
                description.innerText = weather1.description;
                seznam_ul.appendChild(main);
                seznam_ul.appendChild(description);
            }
        });
    }


}

