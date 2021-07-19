<?php

namespace App\Controller;

use App\Database;
use App\Model\Subscription;
use \Exception;

class SubscriptionController
{
    /* @var \App\Database */
    protected Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    /**
     * @param \App\Model\Subscription $subscription
     * @return bool
     * @throws \Exception
     */
    public function create(Subscription $subscription): bool
    {
        try {
            $id = $this->database->Insert(
                "INSERT INTO `" . Subscription::TABLE_NAME . "` (`email`, `created_at`) VALUES (:email, :created_at)",
                [
                    'email' => $subscription->getEmail(),
                    'created_at' => $subscription->getCreatedAt()->format('Y-m-d H:i:s')
                ]
            );

            if ($id !== null || $id !== 0) {
                return 1;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return 0;
    }

    /**
     * @param int $subscriptionId
     * @return bool
     * @throws \Exception
     */
    public function remove(int $subscriptionId): bool
    {
        try {
            $this->database->Remove(
                "DELETE FROM `" . Subscription::TABLE_NAME . "` WHERE id = :id",
                [
                    'id' => $subscriptionId
                ]
            );

            return 1;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param array|null $sortParams
     * @param string|null $searchString
     * @param string|null $emailProviderFilter
     * @return array
     * @throws \Exception
     */
    public function getAll(array $sortParams = null, string $searchString = null, string $emailProviderFilter = null): array
    {
        try {
            $additionalSql = $this->getAdditionalSql($sortParams, $searchString, $emailProviderFilter);

            return $this->database->Select(
                "SELECT * FROM `" . Subscription::TABLE_NAME . "`" . $additionalSql
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string|null $emailProviderFilter
     * @return array
     * @throws \Exception
     */
    public function getEmailsProvider(string $emailProviderFilter = null): array
    {
        try {
            $emailsProviders = [];
            $responseArray = [];

            $subscriptions = $this->database->Select("SELECT * FROM `" . Subscription::TABLE_NAME . "`");

            foreach ($subscriptions as $subscription) {
                $emailArray = explode('@', $subscription['email']);
                $emailsProviders[] = $emailArray[1];
            }
            $emailsProviders = array_unique($emailsProviders);

            foreach ($emailsProviders as $emailsProvider) {
                $status = ($emailsProvider === $emailProviderFilter) ? 'selected' : '';

                $responseArray[] = [
                    'provider' => $emailsProvider,
                    'status' => $status
                ];
            }

            return $responseArray;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param array|null $sortParams
     * @param string|null $searchString
     * @param string|null $emailProviderFilter
     * @return string
     */
    protected function getAdditionalSql(array $sortParams = null, string $searchString = null, string $emailProviderFilter = null): string
    {
        $additionalSqlString = '';
        $sortingSql = '';
        $searchSql = '';
        $emailProviderSql = '';

        if ($sortParams !== null) {
            $sortingSql .= ' ORDER BY ';

            foreach ($sortParams as $sortBy => $sortType)
            {
                $sortingSql .= $sortBy . ' ' . $sortType . ',';
            }
            $sortingSql = substr($sortingSql, 0, -1);
        }

        if ($searchString !== null) {
            $searchSql = " WHERE email LIKE '%" . $searchString . "%'";
        }

        if ($emailProviderFilter !== null) {
            $emailProviderSql = " email LIKE '%" . $emailProviderFilter . "%'";
        }

        if ($searchSql === '' && $emailProviderSql !== '') {
            $additionalSqlString = ' WHERE ' . $emailProviderSql;
        } else if ($searchSql !== '' && $emailProviderSql === '') {
            $additionalSqlString = $searchSql;
        } else if ($searchSql !== '' && $emailProviderSql !== '') {
            $additionalSqlString = $searchSql . " AND " . $emailProviderSql;
        }

        $additionalSqlString .= $sortingSql;

        return $additionalSqlString;
    }
}