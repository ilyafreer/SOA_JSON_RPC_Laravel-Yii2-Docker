SOA example project
database mariaDb

Для клиента выбран yii2 версии basic
composer create-project --prefer-dist yiisoft/yii2-app-basic ./

Для сервера установлен  Laravel
composer create-project --prefer-dist laravel/laravel ./

Пакет для работы с curl запосами
composer require guzzlehttp/guzzle

Все контейнеры объеденены в общую сеть, для отправки запросов между контейнерами по локальной сети обращение к контейнерам без алиасов, по ip
"Containers": {
            "345fc0b60b09744b069de5e3336927d75a98b4aa758885a8dc9f90e52199aeb0": {
                "Name": "client_web",
                "EndpointID": "d0ddab154a3fea0a53485612e5d12444beba019c7d20f76d086913f66aeae8a5",
                "MacAddress": "02:42:ac:14:00:03",
                "IPv4Address": "172.20.0.3/16",
                "IPv6Address": ""
            },
            "7abe3c00bd81557d6f1800a5bc12d45085b90cc735cd68b2295e5335f029464f": {
                "Name": "server_web",
                "EndpointID": "f6708190546ba5abdcead63626d00c74a1cdcfc610312db46ae59338253ca1c2",
                "MacAddress": "02:42:ac:14:00:04",
                "IPv4Address": "172.20.0.4/16",
                "IPv6Address": ""
            },
            "f83d92f8ea215f57b878b3a56f60b0b6b1edd97579c71ed0d156a76bb295a38f": {
                "Name": "database",
                "EndpointID": "6b8fcad4081bda3ad10713ce925efa208a2e1f40583acaf1b269648f06c31787",
                "MacAddress": "02:42:ac:14:00:02",
                "IPv4Address": "172.20.0.2/16",
                "IPv6Address": ""
            }
