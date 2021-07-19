# Magebit - Test work

How to install the local project:

1. Clone the project
2. In the root directory install node modules: `npm install --save-dev`.
3. In the root directory install composer: `composer install`.
4. Import `magebit-test.sql` to your mysql local-server and configure the database consts in `App/Database.php`.
5. Run the following command to build all frontend: `gulp`
6. In another console tab/window run the following command to launch the PHP server: `php -S localhost:3000`
7. In another console tab/window run the following command to watch the project in a browser: `gulp watch`

My npm and node versions: 
```
npm -v
6.14.11

node-v
v14.15.5
```
