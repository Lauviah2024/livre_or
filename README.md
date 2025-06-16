# The Livre_or
This app allows people to write testimonies, messages, comments on customed cards of the rotary Club members.
You just need to submit your name, your club name, your city, your message. And then, the app will generate the card template with your informations in the gallery and download it. With the php graphic draw library, the users informations are drawn on the template in the two case : the case where the user uploaded an image and the case where the user didn't uploaded anything.

## Fonctionnalities
Here are the fonctionnalities :
- Generate the testimonies cards in the galery
- Download your generated card on your device

## Technologies
- PHP Codeigniter 4 with PHP Graphic Draw library for the backend,
- HTML, CSS, Javascript, bootstrap for the frontend

## How to set up the project ?

### 1/ Clone the project :
```bash
git clone https://github.com/Lauviah2024/livre_or.git
cd livre-or
```
### 2/ Install dependencies
```bash
composer install
```
### 3/ Configure your environment
Create a .env file at the root of the project and copy the content of the file env_prod inside.
```
cp env_prod .env
```
### 4/ Import the database
No migrations required. Just import the provided SQL dump:

- Open phpMyAdmin or your preferred MySQL client.
- Create a database (e.g., livre-or)
- Import the SQL file located in /app/sql/livre-or.sql

### 5 / Start the development server
```
php spark serve
```

### Notes
- No database migrations are required.
- Make sure to import the database dump manually.

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
