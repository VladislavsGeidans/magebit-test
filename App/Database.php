<?php
    namespace App;

    use Exception;
    use PDO;
    use PDOStatement;

    /**
     * Class Database
     * @package App
     */
    class Database
    {
        const DATABASE_HOST = 'localhost';
        const DATABASE_NAME = 'magebit-test';
        const DATABASE_USERNAME = 'root';
        const DATABASE_PASSWORD = '';

        private PDO $connection;

        /**
         * @throws Exception
         */
        public function __construct()
        {
            try {
                $this->connection = new PDO("mysql:host=" . self::DATABASE_HOST . ";dbname=" . self::DATABASE_NAME . ";", self::DATABASE_USERNAME, self::DATABASE_PASSWORD);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        /**
         * @param string $statement
         * @param array $parameters
         * @return string
         * @throws Exception
         */
        public function Insert(string $statement = "", array $parameters = []) :string
        {
            try {
                $this->executeStatement($statement, $parameters);

                return $this->connection->lastInsertId();
            } catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        /**
         * @param string $statement
         * @param array $parameters
         * @return array
         * @throws Exception
         */
        public function Select(string $statement = "", array $parameters = []) :array
        {
            try {
                $stmt = $this->executeStatement($statement, $parameters);

                return $stmt->fetchAll();
            } catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        /**
         * @param string $statement
         * @param array $parameters
         * @throws Exception
         */
        public function Update(string $statement = "", array $parameters = [])
        {
            try {
                $this->executeStatement($statement, $parameters);
            } catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        /**
         * @param string $statement
         * @param array $parameters
         * @throws Exception
         */
        public function Remove(string $statement = "", array $parameters = []) :void
        {
            try {
                $this->executeStatement($statement, $parameters);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        /**
         * @param string $statement
         * @param array $parameters
         * @return false|PDOStatement
         * @throws Exception
         */
        private function executeStatement(string $statement = "", array $parameters = [])
        {
            try {
                $stmt = $this->connection->prepare($statement);
                $stmt->execute($parameters);

                return $stmt;
            } catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }